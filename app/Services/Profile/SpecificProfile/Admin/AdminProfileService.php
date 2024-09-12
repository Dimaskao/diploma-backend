<?php

namespace App\Services\Profile\SpecificProfile\Admin;

use App\Services\Image\ImageUploadService;
use App\Services\Profile\SpecificProfile\Admin\Handlers\Delete\DeleteHandler;
use App\Services\Profile\SpecificProfile\Admin\Handlers\Get\GetHandler;
use App\Services\Profile\SpecificProfile\Admin\Handlers\Update\UpdateHandler;
use App\Services\Profile\SpecificProfile\BaseSpecificProfileService;
use App\Services\Response\ResponseService;
use App\Services\Validation\ValidationService;

class AdminProfileService extends BaseSpecificProfileService
{
    use GetHandler, UpdateHandler, DeleteHandler;

    public function __construct(ValidationService $validationService, ResponseService $responseService, ImageUploadService $imageUploadService)
    {
        parent::__construct($validationService, $responseService, $imageUploadService);
    }

    protected function specificProfileUser($user): mixed
    {
        return $user->userProfile->admin;
    }
}
