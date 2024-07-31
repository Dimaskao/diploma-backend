<?php

namespace App\Services\Profile\SpecificProfile\Admin\Handlers\Get\Helpers;

use App\Enums\Permission;
use App\Models\Admin;
use Illuminate\Support\Facades\Log;

trait ProfileGetHelper
{
    protected function getAdminProfileData($admin, $base): array
    {
        return [
            'id' => $base->id,
            'name' => $admin->name,
            'permissions' => $this->getProfilePermissions($admin),
            'email' => $base->email,
            'avatar_url' => $base->avatar_url,
        ];
    }

    private function getProfilePermissions(Admin $admin): array
    {
        return array_filter(json_decode($admin->permissions, true), function ($permission) {
            return in_array($permission, [
                Permission::READ,
                Permission::WRITE,
                Permission::EDIT,
                Permission::FULL,
            ]);
        });
    }
}
