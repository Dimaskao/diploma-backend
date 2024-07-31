<?php

namespace App\Services\Profile\SpecificProfile\Company;

use App\Services\Profile\SpecificProfile\BaseSpecificProfileService;
use App\Services\Profile\SpecificProfile\Company\Handlers\Delete\DeleteHandler;
use App\Services\Profile\SpecificProfile\Company\Handlers\Get\GetHandler;
use App\Services\Profile\SpecificProfile\Company\Handlers\Update\UpdateHandler;

class CompanyProfileService extends BaseSpecificProfileService
{
    use GetHandler, UpdateHandler, DeleteHandler;

    protected function specificProfileUser($user): mixed
    {
        return $user->userProfile->company;
    }
}
