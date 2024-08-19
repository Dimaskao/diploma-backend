<?php

namespace App\Factories;

use App\Enums\UserRole;
use App\Interfaces\Factory;
use App\Interfaces\ProfileStrategy;
use App\Models\User;
use App\Services\Image\ImageProcessingService;
use App\Services\Image\ImageUploadService;
use App\Services\Profile\SpecificProfile\Admin\AdminProfileService;
use App\Services\Profile\SpecificProfile\Company\CompanyProfileService;
use App\Services\Profile\SpecificProfile\RegularUser\RegularUserProfileService;
use App\Services\Response\ResponseService;
use App\Services\Validation\ValidationService;
use App\Strategies\Profile\SpecificProfile\AdminProfileStrategy;
use App\Strategies\Profile\SpecificProfile\CompanyProfileStrategy;
use App\Strategies\Profile\SpecificProfile\RegularUserProfileStrategy;
use Exception;
use Illuminate\Support\Facades\Log;

class ProfileStrategyFactory implements Factory
{
    protected ValidationService $validationService;
    protected ResponseService $responseService;
    protected ImageUploadService $imageUploadService;

    public function __construct(ValidationService $validationService, ResponseService $responseService, ImageUploadService $imageUploadService)
    {
        $this->validationService = $validationService;
        $this->responseService = $responseService;
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * @throws Exception
     */
    public function create($params = []) : ProfileStrategy
    {
        if (isset($params['id'])) {
            $user = User::find($params['id']);

            if (!$user) {
                throw new Exception('User not found');
            }

            return match ($user->role->name) {
                UserRole::REGULAR_USER => new RegularUserProfileStrategy(new RegularUserProfileService($this->validationService, $this->responseService, $this->imageUploadService)),
                UserRole::COMPANY => new CompanyProfileStrategy(new CompanyProfileService($this->validationService, $this->responseService, $this->imageUploadService)),
                UserRole::ADMIN => new AdminProfileStrategy(new AdminProfileService($this->validationService, $this->responseService, $this->imageUploadService)),
                default => throw new Exception('Invalid user role'),
            };
        }
        throw new Exception('Id not found');
    }
}
