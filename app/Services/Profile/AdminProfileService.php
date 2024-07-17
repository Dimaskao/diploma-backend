<?php

namespace App\Services\Profile;

use App\Enums\Edit;
use App\Enums\Permission;
use App\Enums\ResponseKeys;
use App\Enums\UpdateType;
use App\Models\Admin;
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

        return response()->json([
            ResponseKeys::PROFILE => [
                'id' => $user->id,
                'name' => $admin->name,
                'permissions' => $this->getProfilePermissions($admin),
                'email' => $user->email,
                'avatar_url' => $user->avatar_url
            ]
        ], 200);
    }

    public function updateProfile(User $user, Request $request): JsonResponse
    {
        if ($request->has(UpdateType::UPDATE_TYPE)) {
            try {
                $admin = $user->profilable;
                return response()->json([
                    ResponseKeys::MESSAGE => 'Company information was updated successfully',
                    ResponseKeys::UPDATED_INFORMATION => $this->updateByEditInfoType($request, $admin, $user)
                ], 200);
            } catch (Exception $e) {
                return response()->json([ResponseKeys::MESSAGE => $e->getMessage()], 500);
            }
        }

        return response()->json([ResponseKeys::MESSAGE => 'Unset update type'], 400);
    }

    public function deleteProfile($id): JsonResponse
    {
        return response()->json([ResponseKeys::MESSAGE => 'Unset update type'], 400);
    }

    private function getProfilePermissions(Admin $admin): array
    {
        $permissions = $admin->permissions;
        return [
            Permission::READ => $permissions[Permission::READ],
            Permission::WRITE => $permissions[Permission::WRITE],
            Permission::EDIT => $permissions[Permission::EDIT],
            Permission::FULL => $permissions[Permission::FULL],
        ];
    }

    /**
     * @throws Exception
     */
    private function updateByEditInfoType(Request $request, Admin $admin, User $user): array
    {
        if ($request->has(Edit::EDIT_INFO)) {
            $editRequest = $request->get(Edit::EDIT_INFO);
            return match ($editRequest) {
                Edit::SELF => $this->updateSelfByUpdateType($editRequest, $admin, $user, []),
                Edit::ANOTHER_ADMIN_PERMISSIONS => $this->updateAnotherAdminPermissions($editRequest, $admin, $user, []),
                Edit::REGULAR_USER => $this->updateRegularUser($editRequest, $admin, $user),
                Edit::COMPANY => $this->updateCompany($editRequest, $admin, $user),
                Edit::BAN_USER => $this->banUser($editRequest, $admin, $user),
                Edit::BAN_POST => $this->banPost($editRequest, $admin, $user),
                Edit::UNBAN_USER => $this->unbanPost($editRequest, $admin, $user),
                Edit::ADD_NEW_SKILL => $this->addNewSkill($editRequest, $admin, $user),
                Edit::UNBAN_POST => $this->unbanUser($editRequest, $admin, $user),
                default => throw new Exception('Update type does not exist')
            };
        }
        return [];
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
        $data = $this->validator->validate($personalInformation, [
            'name' => 'sometimes|string',
            'password' => 'sometimes|string|min:8',
            'avatar_url' => 'sometimes|url'
        ]);

        if ($admin && $user) {
            $adminUpdateData = $this->getSelfAdminUpdateData($data);
            $baseUserUpdateData = $this->getUserUpdateData($data);

            if (!empty($adminUpdateData)) {
                $admin->update($adminUpdateData);
            }

            if (!empty($baseUserUpdateData)) {
                $user->update($baseUserUpdateData);
            }
        } else {
            throw new Exception('Error while updating user profile');
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
     * @param $editRequest
     * @param Admin $admin
     * @param User $user
     * @param $updatedResults
     * @return array
     * @throws ValidationException
     */
    private function updateSelfByUpdateType($editRequest, Admin $admin, User $user, $updatedResults): array
    {
        if (isset($editRequest[UpdateType::PERSONAL_INFORMATION])) {
            $this->updatePersonalInformation($editRequest[UpdateType::PERSONAL_INFORMATION], $admin, $user);
            $updatedResults[UpdateType::PERSONAL_INFORMATION] = [
                'name' => $admin,
                'avatar_url' => $user->avatar_url
            ];
        }

        return $updatedResults;
    }

    /**
     * @throws Exception
     */
    private function updateAnotherAdminPermissions(mixed $editRequest, Admin $admin, User $user, $updatedResults): array
    {
        if (isset($editRequest['update_admin_id'])) {
            $adminToUpdate = User::where('id', $editRequest['update_admin_id'])->first();

            if (!$adminToUpdate || $adminToUpdate->profileable_type !== Admin::class) {
                throw new Exception('Incorrect admin ID was sent');
            }

            if ($admin->permissions->keys()->contains(Permission::FULL)) {
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
            } else {
                throw new Exception('Admin does not have full permissions');
            }
        }

        return $updatedResults;
    }


    private function banUser(mixed $editRequest, Admin $admin, User $user): array
    {
        return [];
    }

    private function banPost(mixed $editRequest, Admin $admin, User $user): array
    {
        return [];
    }

    private function addNewSkill(mixed $editRequest, Admin $admin, User $user): array
    {
        return [];
    }

    private function unbanPost(mixed $editRequest, Admin $admin, User $user): array
    {
        return [];
    }

    private function unbanUser(mixed $editRequest, Admin $admin, User $user): array
    {
        return [];
    }
}
