<?php

namespace App\Services\Profile\SpecificProfile\Admin;

use App\Services\Profile\SpecificProfile\Admin\Handlers\Delete\DeleteHandler;
use App\Services\Profile\SpecificProfile\Admin\Handlers\Get\GetHandler;
use App\Services\Profile\SpecificProfile\Admin\Handlers\Update\UpdateHandler;
use App\Services\Profile\SpecificProfile\BaseSpecificProfileService;

class AdminProfileService extends BaseSpecificProfileService
{
    use GetHandler, UpdateHandler, DeleteHandler;

    protected function specificProfileUser($user): mixed
    {
        return $user->userProfile->admin;
    }
}
