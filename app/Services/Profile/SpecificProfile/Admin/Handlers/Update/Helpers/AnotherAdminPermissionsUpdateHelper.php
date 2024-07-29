<?php

namespace App\Services\Profile\SpecificProfile\Admin\Handlers\Update\Helpers;

use App\Enums\Permission;
use App\Enums\ResponseKey;
use App\Models\Admin;
use App\Models\User;
use Exception;

trait AnotherAdminPermissionsUpdateHelper
{
    /**
     * @throws Exception
     */
    protected function updateAnotherAdminPermissions($editRequest, Admin $admin): array
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
        return [ResponseKey::MESSAGE => 'success'];
    }


}
