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
        return $this->responseService->response(ResponseKeys::PROFILE, [
            'id' => $user->id,
            'name' => $admin->name,
            'permissions' => $this->getProfilePermissions($admin),
            'email' => $user->email,
            'avatar_url' => $user->avatar_url
        ], 200);
    }

    public function updateProfile(User $user, Request $request): JsonResponse
    {
        if ($request->has(UpdateType::UPDATE_TYPE)) {
            try {
                $admin = $user->profilable;
                return $this->responseService->response(ResponseKeys::RESULT, [
                    ResponseKeys::MESSAGE => 'Information was updated successfully',
                    ResponseKeys::UPDATED_INFORMATION => $this->updateByEditInfoType($request, $admin, $user)
                ], 200);
            } catch (Exception $e) {
                return $this->responseService->response(ResponseKeys::ERROR, $e->getMessage(), 500);
            }
        }
        return $this->responseService->response(ResponseKeys::ERROR, "Unset update type", 400);
    }

    public function deleteProfile($id): JsonResponse
    {
        $baseUser = User::find($id);
        if ($baseUser) {
            $company = $baseUser->profileable;

            $jobOffers = JobOffer::where('company_id', $company->id)->get();
            foreach ($jobOffers as $jobOffer) {
                JobOfferSkill::where('job_offer_id', $jobOffer->id)->delete();
                $jobOffer->delete();
            }

            $posts = Post::where('user_id', $baseUser->id)->get();
            foreach ($posts as $post) {
                PostImage::where('post_id', $post->id)->delete();
                $post->delete();
            }

            $company->delete();
            $baseUser->delete();

            return $this->responseService->response(ResponseKeys::MESSAGE, "Company profile was deleted", 200);
        } else {
            return $this->responseService->response(ResponseKeys::ERROR, "User not found", 404);
        }
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
//                Edit::REGULAR_USER => $this->updateRegularUser($editRequest, $admin, $user),
//                Edit::COMPANY => $this->updateCompany($editRequest, $admin, $user),
                Edit::BAN_USER => $this->banUser($editRequest, $admin),
                Edit::BAN_POST => $this->banPost($editRequest, $admin),
                Edit::UNBAN_USER => $this->unbanPost($editRequest, $admin),
                Edit::ADD_NEW_SKILL => $this->addNewSkills($editRequest, $admin),
                Edit::UNBAN_POST => $this->unbanUser($editRequest, $admin),
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

    /**
     * @throws Exception
     */
    private function banUser(mixed $editRequest, Admin $admin): array
    {
        if (isset($editRequest['user_id_to_ban']) && $this->isHavePermissionToBanOrUnban($admin)) {
            $user = User::find($editRequest['user_id_to_ban']);

            if ($user && isset($editRequest['reason'])) {
                $data = [
                    'user_id' => $user->id,
                    'banned_by_admin_id' => $admin->id,
                    'reason' => $editRequest['reason'],
                    'date_banned' => time()
                ];

                if (isset($editRequest['valid_until'])) {
                    $data['valid_until'] = $editRequest['valid_until']; // else banned forever
                }

                BannedUser::insert($data);

                event(new UserBanned($user));

                return [ResponseKeys::MESSAGE => 'success'];
            } else {
                throw new Exception('User to ban does not exist or the reason was not set');
            }
        }

        throw new Exception('Admin does not have full permissions or the ID of the user to ban was not sent');
    }

    /**
     * @throws Exception
     */
    private function banPost(mixed $editRequest, Admin $admin): array
    {
        if (isset($editRequest['post_id_to_ban']) && $this->isHavePermissionToBanOrUnban($admin)) {
            $post = Post::find($editRequest['post_id_to_ban']);

            if ($post && isset($editRequest['reason'])) {
                $data = [
                    'post_id' => $post->id,
                    'banned_by_admin_id' => $admin->id,
                    'reason' => $editRequest['reason'],
                    'date_banned' => time()
                ];

                if (isset($editRequest['valid_until'])) {
                    $data['valid_until'] = $editRequest['valid_until']; // else banned forever
                }

                BannedPost::insert($data);
                return [ResponseKeys::MESSAGE => 'success'];
            } else {
                throw new Exception('Post to ban does not exist or the reason was not set');
            }
        }

        throw new Exception('Admin does not have full permissions or the ID of the post to ban was not sent');
    }

    /**
     * @throws Exception
     */
    private function addNewSkills(mixed $editRequest, Admin $admin): array
    {
        if (isset($editRequest['skills'])) {
            $skills = $editRequest['skills'];

            foreach ($skills as $skill) {
                if (isset($skill['name'])) {
                    Skill::insert(['name' => $skill['name']]);
                } else {
                    throw new Exception('Skill name was not set');
                }
            }
            return [ResponseKeys::MESSAGE => 'success'];
        }
        return [ResponseKeys::ERROR => 'Skills were not set'];
    }

    /**
     * @throws Exception
     */
    private function unbanPost(mixed $editRequest, Admin $admin): array
    {
        if (isset($editRequest['post_id_to_unban']) && $this->isHavePermissionToBanOrUnban($admin)) {
            BannedPost::where($editRequest['post_id_to_unban'])->first()->delete();
            return [ResponseKeys::MESSAGE => 'success'];
        }

        throw new Exception('Admin does not have full permissions or the ID of the post to unban was not sent');
    }

    /**
     * @throws Exception
     */
    private function unbanUser(mixed $editRequest, Admin $admin): array
    {
        if (isset($editRequest['user_id_to_unban']) && $this->isHavePermissionToBanOrUnban($admin)) {
            BannedUser::where($editRequest['user_id_to_unban'])->first()->delete();
            return [ResponseKeys::MESSAGE => 'success'];
        }
        throw new Exception('Admin does not have full permissions or the ID of the user to ban was not sent');
    }

    private function isHavePermissionToBanOrUnban(Admin $admin): bool
    {
        $permissionsToBan = [
            Permission::FULL
        ];

        foreach ($permissionsToBan as $permission) {
            if (isset($admin->permissions[$permission])) {
                return true;
            }
        }

        return false;
    }
}
