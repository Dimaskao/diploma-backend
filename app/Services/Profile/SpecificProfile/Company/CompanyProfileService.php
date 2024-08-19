<?php

namespace App\Services\Profile\SpecificProfile\Company;

use App\Services\Image\ImageUploadService;
use App\Services\Profile\SpecificProfile\BaseSpecificProfileService;
use App\Services\Profile\SpecificProfile\Company\Handlers\Delete\DeleteHandler;
use App\Services\Profile\SpecificProfile\Company\Handlers\Get\GetHandler;
use App\Services\Profile\SpecificProfile\Company\Handlers\Update\UpdateHandler;
use App\Services\Response\ResponseService;
use App\Services\Validation\ValidationService;

class CompanyProfileService extends BaseSpecificProfileService
{
    use GetHandler, UpdateHandler, DeleteHandler;

    public function __construct(ValidationService $validationService, ResponseService $responseService, ImageUploadService $imageUploadService)
    {
        parent::__construct($validationService, $responseService, $imageUploadService);
    }
    protected function specificProfileUser($user): mixed
    {
        return $user->userProfile->company;
    }
}
