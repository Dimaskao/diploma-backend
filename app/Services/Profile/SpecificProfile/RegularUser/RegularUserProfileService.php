<?php

namespace App\Services\Profile\SpecificProfile\RegularUser;

use App\Services\Profile\SpecificProfile\BaseSpecificProfileService;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Delete\DeleteHandler;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Get\GetHandler;
use App\Services\Profile\SpecificProfile\RegularUser\Handlers\Update\UpdateHandler;

class RegularUserProfileService extends BaseSpecificProfileService
{
    use GetHandler, UpdateHandler, DeleteHandler;

    protected function specificProfileUser($user): mixed
    {
        return $user->userProfile->regularUser;
    }
}
