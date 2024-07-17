<?php

namespace App\Services\Profile;

use App\Enums\EditInfoType;
use App\Enums\Permission;
use App\Enums\ResponseKeys;
use App\Enums\UpdateType;
use App\Interfaces\SpecificProfileService;
use App\Models\Admin;
use App\Models\User;
use App\Services\ValidationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminProfileService implements SpecificProfileService
{
    protected ValidationService $validator;

    public function __construct()
    {
        $this->validator = new ValidationService();
    }

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
                    ResponseKeys::UPDATED_INFORMATION => $this->updateByUpdateType($request, $admin, $user)
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
    private function updateByUpdateType(Request $request, Admin $admin, User $user): array
    {
        if (isset($request[EditInfoType::EDIT_INFO])) {
            match ($request[EditInfoType::EDIT_INFO]) {
                EditInfoType::SELF => $this->updateSelf($request, $admin, $user),
                EditInfoType::ANOTHER_ADMIN => $this->updateAnotherAdmin($request, $admin, $user),
                EditInfoType::REGULAR_USER => $this->updateRegularUser($request, $admin, $user),
                EditInfoType::COMPANY => $this->updateCompany($request, $admin, $user),
                default => throw new Exception('Update type does not exist')
            };
        }
        return [];
    }

    private function updateSelf(Request $request, Admin $admin, User $user)
    {

    }

    private function updateAnotherAdmin(Request $request, Admin $admin, User $user)
    {
    }

    private function updateRegularUser(Request $request, Admin $admin, User $user)
    {
    }

    private function updateCompany(Request $request, Admin $admin, User $user)
    {
    }
}
