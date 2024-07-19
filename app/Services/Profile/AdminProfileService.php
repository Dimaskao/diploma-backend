<?php

namespace App\Services\Profile;

use App\Enums\Edit;
use App\Enums\Permission;
use App\Enums\ResponseKeys;
use App\Enums\UpdateType;
use App\Events\UserBanned;
use App\Models\Admin;
use App\Models\BannedPost;
use App\Models\BannedUser;
use App\Models\JobOffer;
use App\Models\JobOfferSkill;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\Skill;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminProfileService extends BaseSpecificProfileService
{
    public function getProfile(User $user): JsonResponse
    {
        $admin = $user->profileable;
        return $this->responseService->success([
            ResponseKeys::PROFILE => [
                ResponseKeys::ADMIN => [
                    'id' => $user->id,
                    'name' => $admin->name,
                    'permissions' => $this->getProfilePermissions($admin),
                    'email' => $user->email,
                    'avatar_url' => $user->avatar_url
                ]
            ]
        ]);
    }

    public function updateProfile(User $user, Request $request): JsonResponse
    {
        if (!$request->has(UpdateType::UPDATE_TYPE)) {
            return $this->responseService->badRequest("Unset update type");
        }

        try {
            return $this->responseService->success([ResponseKeys::UPDATED_INFORMATION => $this->updateByEditInfoType($request, $user->profileable, $user)]);
        } catch (ValidationException $e) {
            return $this->responseService->badRequest($e->getMessage());
        } catch (Exception $e) {
            return $this->responseService->internalServerError($e->getMessage());
        }
    }

    public function deleteProfile($id): JsonResponse
    {
        return $this->responseService->notFound("User not found");
    }

    private function getProfilePermissions(Admin $admin): array
    {
        return array_filter($admin->permissions, function ($permission) {
            return in_array($permission, [
                Permission::READ,
                Permission::WRITE,
                Permission::EDIT,
                Permission::FULL,
            ]);
        });
    }

    /**
     * @throws Exception
     */
    private function updateByEditInfoType(Request $request, Admin $admin, User $user): array
    {
        if (!$request->has(Edit::EDIT_INFO)) {
            return [];
        }

        $editRequest = $request->get(Edit::EDIT_INFO);
        return match ($editRequest) {
            Edit::SELF => $this->updateSelfByUpdateType($editRequest, $admin, $user),
            Edit::ANOTHER_ADMIN_PERMISSIONS => $this->updateAnotherAdminPermissions($editRequest, $admin, $user),
            Edit::BAN_USER => $this->banUser($editRequest, $admin),
            Edit::BAN_POST => $this->banPost($editRequest, $admin),
            Edit::UNBAN_USER => $this->unbanUser($editRequest, $admin),
            Edit::ADD_NEW_SKILLS => $this->addNewSkills($editRequest),
            Edit::REMOVE_SKILLS => $this->removeSkills($editRequest),
            Edit::UNBAN_POST => $this->unbanPost($editRequest, $admin),
//            Edit::REGULAR_USER => $this->updateRegularUser($editRequest, $admin, $user),
//            Edit::COMPANY => $this->updateCompany($editRequest, $admin, $user),
            default => throw new Exception('Update type does not exist')
        };
    }

    /**
     * @param $editRequest
     * @param Admin $admin
     * @param User $user
     * @return array
     * @throws ValidationException
     */
    private function updateSelfByUpdateType($editRequest, Admin $admin, User $user): array
    {
        $updatedResults = [];
        if (isset($editRequest[UpdateType::PERSONAL_INFORMATION])) {
            $this->updatePersonalInformation($editRequest[UpdateType::PERSONAL_INFORMATION], $admin, $user);
            $updatedResults[UpdateType::PERSONAL_INFORMATION] = [
                'name' => $admin,
                'avatar_url' => $user->avatar_url
            ];
        }

        return $updatedResults;
    }

    private function updateRegularUser($editRequest, Admin $admin, User $user): array
    {
        return [];
    }

    private function updateCompany($editRequest, Admin $admin, User $user): array
    {
        return [];
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    private function updatePersonalInformation($personalInformation, $admin, $user)
    {
        $data = $this->validator->validate($personalInformation, $this->updatePersonalInformationValidationRules());

        if (!$admin || !$user) {
            throw new Exception('Error while updating user profile');
        }

        $adminUpdateData = $this->getSelfAdminUpdateData($data);
        $baseUserUpdateData = $this->getUserUpdateData($data);

        if ($adminUpdateData) {
            $admin->update($adminUpdateData);
        }

        if ($baseUserUpdateData) {
            $user->update($baseUserUpdateData);
        }
    }

    /**
     * @param $personalInformation
     * @return array
     */
    private function getSelfAdminUpdateData($personalInformation): array
    {
        $adminUpdateData = [];

        if (isset($personalInformation['name'])) {
            $adminUpdateData['name'] = $personalInformation['name'];
        }

        return $adminUpdateData;
    }

    /**
     * @throws Exception
     */
    private function updateAnotherAdminPermissions(mixed $editRequest, Admin $admin, User $user): array
    {
        if (!isset($editRequest['update_admin_id'])) {
            return [];
        }

        $adminToUpdate = User::where('id', $editRequest['update_admin_id'])->first();

        if (!$adminToUpdate || $adminToUpdate->profileable_type !== Admin::class) {
            throw new Exception('Incorrect admin ID was sent');
        }

        if (!$admin->permissions->keys()->contains(Permission::FULL)) {
            throw new Exception('Admin does not have full permissions');
        }

        $permissions = [
            Permission::EDIT,
            Permission::READ,
            Permission::WRITE,
            Permission::FULL
        ];

        foreach ($permissions as $permission) {
            if (isset($editRequest[$permission])) {
                $adminToUpdate->profileable->permissions[$permission] = (bool)$editRequest[$permission];
            }
        }

        $adminToUpdate->profileable->save();
        return [ResponseKeys::MESSAGE => 'success'];
    }

    private function banUser($editRequest, Admin $admin): array
    {
        return $this->handleBanOrUnban($editRequest, $admin, 'user_id_to_ban', BannedUser::class);
    }

    private function banPost($editRequest, Admin $admin): array
    {
        return $this->handleBanOrUnban($editRequest, $admin, 'post_id_to_ban', BannedPost::class);
    }

    private function unbanUser($editRequest, Admin $admin): array
    {
        return $this->handleUnban($editRequest, $admin, 'user_id_to_unban', BannedUser::class);
    }

    private function unbanPost($editRequest, Admin $admin): array
    {
        return $this->handleUnban($editRequest, $admin, 'post_id_to_unban', BannedPost::class);
    }

    /**
     * @throws Exception
     */
    private function addNewSkills(mixed $editRequest): array
    {
        if (!isset($editRequest['skills'])) {
            return [ResponseKeys::ERROR => 'Skills were not set'];
        }

        foreach ($editRequest['skills'] as $skill) {
            if (!isset($skill['name'])) {
                throw new Exception('Skill name was not set');
            }
            Skill::insert(['name' => $skill['name']]);
        }

        return [ResponseKeys::MESSAGE => 'success'];
    }

    private function handleBanOrUnban($editRequest, Admin $admin, string $idKey, string $modelClass): array
    {
        if (!isset($editRequest[$idKey]) || !$this->hasFullPermission($admin)) {
            throw new Exception("Admin does not have full permissions or the ID of the entity to ban/unban was not sent");
        }

        $entity = User::find($editRequest[$idKey]);

        if (!$entity || !isset($editRequest['reason'])) {
            throw new Exception("Entity to ban does not exist or the reason was not set");
        }

        $data = [
            'user_id' => $entity->id,
            'banned_by_admin_id' => $admin->id,
            'reason' => $editRequest['reason'],
            'date_banned' => time()
        ];

        if (isset($editRequest['valid_until'])) {
            $data['valid_until'] = $editRequest['valid_until'];
        }

        $modelClass::insert($data);

        if ($modelClass === BannedUser::class) {
            event(new UserBanned($entity));
        }

        return [ResponseKeys::MESSAGE => 'success'];
    }

    /**
     * @throws Exception
     */
    private function handleUnban($editRequest, Admin $admin, string $idKey, string $modelClass): array
    {
        if (!isset($editRequest[$idKey]) || !$this->hasFullPermission($admin)) {
            throw new Exception("Admin does not have full permissions or the ID of the entity to unban was not sent");
        }

        $modelClass::where('user_id', $editRequest[$idKey])->first()->delete();
        return [ResponseKeys::MESSAGE => 'success'];
    }

    private function hasFullPermission(Admin $admin): bool
    {
        return isset($admin->permissions[Permission::FULL]);
    }

    private function updatePersonalInformationValidationRules(): array
    {
        return [
            'name' => 'sometimes|string',
            'password' => 'sometimes|string|min:8',
            'avatar_url' => 'sometimes|url'
        ];
    }

    /**
     * @throws Exception
     */
    private function removeSkills(mixed $editRequest)
    {
        if (!isset($editRequest['skills'])) {
            return [ResponseKeys::ERROR => 'Skills were not set'];
        }

        foreach ($editRequest['skills'] as $skill) {
            if (!isset($skill['name'])) {
                throw new Exception('Skill name was not set');
            }
            Skill::where('name', $skill['name'])->first()->delete();
        }

        return [ResponseKeys::MESSAGE => 'success'];
    }
}
