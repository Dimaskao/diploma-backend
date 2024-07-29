<?php

namespace App\Services\Profile\SpecificProfile\Admin\Handlers\Update\Helpers;

use App\Enums\Permission;
use App\Enums\ResponseKey;
use App\Models\Admin;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

trait AnotherAdminPermissionsUpdateHelper
{
    /**
     * @throws Exception
     */
    protected function updateAnotherAdminPermissions($requestData, Admin $admin): array
    {
        $permissions = json_decode($admin->permissions, true);

        if (!$this->isValidRequestData($requestData, $permissions)) {
            throw new Exception('Incorrect updating admin initial parameters were sent');
        }

        $updatingUser = User::find($requestData['update_admin_id'])->userProfile->admin;
        $this->updatePermissions($requestData['permissions'], $updatingUser);
        $updatingUser->save();

        return [ResponseKey::MESSAGE => 'success'];
    }

    private function getAllPermissions(): array
    {
        return [
            Permission::EDIT,
            Permission::READ,
            Permission::WRITE,
            Permission::FULL
        ];
    }

    private function isValidRequestData($requestData, $permissions): bool
    {
        // is current admin has possibility to update another admin permissions
        return isset($requestData['update_admin_id']) && isset($permissions[Permission::FULL]);
    }

    private function updatePermissions($requestData, $updatingUser): void
    {
        $permissions = $this->getAllPermissions();
        foreach ($permissions as $permission) {
            if (isset($requestData[$permission])) {
                $permissions[$permission] = (bool)$requestData[$permission];
            }
        }
        $updatingUser->permissions = json_encode($permissions);
    }
}
