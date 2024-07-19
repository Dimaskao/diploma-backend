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
                    'avatar_url' => $user->avatar_url,
                ],
            ],
        ]);
    }

    public function updateProfile(User $user, Request $request): JsonResponse
    {
        if (!$request->has(UpdateType::UPDATE_TYPE)) {
            return $this->responseService->badRequest("Unset update type");
        }

        try {
            $updatedInfo = $this->updateByEditInfoType($request, $user->profileable, $user);
            return $this->responseService->success([ResponseKeys::UPDATED_INFORMATION => $updatedInfo]);
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
            Edit::ANOTHER_ADMIN_PERMISSIONS => $this->updateAnotherAdminPermissions($editRequest, $admin),
            Edit::BAN_USER => $this->banUser($editRequest, $admin),
            Edit::BAN_POST => $this->banPost($editRequest, $admin),
            Edit::UNBAN_USER => $this->unbanUser($editRequest, $admin),
            Edit::ADD_NEW_SKILLS => $this->addNewSkills($editRequest),
            Edit::REMOVE_SKILLS => $this->removeSkills($editRequest),
            Edit::UNBAN_POST => $this->unbanPost($editRequest, $admin),
            default => throw new Exception('Update type does not exist'),
        };
    }

    /**
     * @throws ValidationException
     */
    private function updateSelfByUpdateType($editRequest, Admin $admin, User $user): array
    {
        $updatedResults = [];
        if (isset($editRequest[UpdateType::PERSONAL_INFORMATION])) {
            $this->updatePersonalInformation($editRequest[UpdateType::PERSONAL_INFORMATION], $admin, $user);
            $updatedResults[UpdateType::PERSONAL_INFORMATION] = [
                'name' => $admin->name,
                'avatar_url' => $user->avatar_url,
            ];
        }

        return $updatedResults;
    }

    /**
     * @throws Exception
     */
    private function updateAnotherAdminPermissions($editRequest, Admin $admin): array
    {
        $adminToUpdate = User::findOrFail($editRequest['update_admin_id']);

        if ($adminToUpdate->profileable_type !== Admin::class) {
            throw new Exception('Incorrect admin ID was sent');
        }

        if (!isset($admin->permissions[Permission::FULL])) {
            throw new Exception('Admin does not have full permissions');
        }

        foreach ([Permission::EDIT, Permission::READ, Permission::WRITE, Permission::FULL] as $permission) {
            if (isset($editRequest[$permission])) {
                $adminToUpdate->profileable->permissions[$permission] = (bool) $editRequest[$permission];
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
    private function addNewSkills($editRequest): array
    {
        foreach ($editRequest['skills'] as $skill) {
            Skill::create(['name' => $skill['name']]);
        }

        return [ResponseKeys::MESSAGE => 'success'];
    }

    /**
     * @throws Exception
     */
    private function handleBanOrUnban($editRequest, Admin $admin, string $idKey, string $modelClass): array
    {
        if (!isset($editRequest[$idKey]) || !$this->hasFullPermission($admin)) {
            throw new Exception("Admin does not have full permissions or the ID of the entity to ban/unban was not sent");
        }

        $entity = User::findOrFail($editRequest[$idKey]);

        $data = [
            'user_id' => $entity->id,
            'banned_by_admin_id' => $admin->id,
            'reason' => $editRequest['reason'],
            'date_banned' => time(),
            'valid_until' => $editRequest['valid_until'] ?? null,
        ];

        match ($modelClass) {
            BannedPost::class => BannedPost::create($data),
            BannedUser::class => BannedUser::create($data),
            default => throw new Exception("Unknown model class")
        };

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

        match ($modelClass) {
            BannedPost::class => BannedPost::where('post_id', $editRequest[$idKey])->firstOrFail()->delete(),
            BannedUser::class => BannedUser::where('user_id', $editRequest[$idKey])->firstOrFail()->delete(),
            default => throw new Exception("Unknown model class")
        };

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
            'avatar_url' => 'sometimes|url',
        ];
    }

    /**
     * @throws ValidationException
     */
    private function updatePersonalInformation($personalInformation, Admin $admin, User $user)
    {
        $data = $this->validator->validate($personalInformation, $this->updatePersonalInformationValidationRules());

        $admin->update($this->getSelfAdminUpdateData($data));
        $user->update($this->getUserUpdateData($data));
    }

    private function getSelfAdminUpdateData($personalInformation): array
    {
        return array_filter(['name' => $personalInformation['name'] ?? null]);
    }

    /**
     * @throws Exception
     */
    private function removeSkills($editRequest)
    {
        foreach ($editRequest['skills'] as $skill) {
            Skill::where('name', $skill['name'])->firstOrFail()->delete();
        }

        return [ResponseKeys::MESSAGE => 'success'];
    }
}
