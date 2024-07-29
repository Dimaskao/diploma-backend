<?php

namespace App\Services\Profile\SpecificProfile\Admin\Handlers\Get\Helpers;

use App\Enums\Permission;
use App\Models\Admin;

trait ProfileGetHelper
{
    protected function getAdminProfileData($user, $admin): array
    {
        return [
            'id' => $user->id,
            'name' => $admin->name,
            'permissions' => $this->getProfilePermissions($admin),
            'email' => $user->email,
            'avatar_url' => $user->avatar_url,
        ];
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
}
