<?php

namespace Services\Profile;

use App\Enums\UserRole;
use App\Factories\ProfileStrategyFactory;
use App\Services\Image\ImageProcessingService;
use App\Services\Image\ImageUploadService;
use App\Services\Profile\SpecificProfile\Admin\AdminProfileService;
use App\Services\Profile\SpecificProfile\Company\CompanyProfileService;
use App\Services\Profile\SpecificProfile\RegularUser\RegularUserProfileService;
use App\Services\Response\ResponseService;
use App\Services\Validation\ValidationService;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;
use TestsHelpers\Profile\UserProfileServiceTestsHelper;

class ProfileServiceTest extends TestCase
{
    use UserProfileServiceTestsHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $factory = new ProfileStrategyFactory();
//        $this->setUpUserProfileService($factory, new RegularUserProfileService(), UserRole::REGULAR_USER);
//        $this->setUpUserProfileService($factory, new CompanyProfileService(), UserRole::COMPANY);
        $validationService = new ValidationService();
        $responseService = new ResponseService();
        $imageService = new ImageUploadService(new ImageProcessingService());

        $this->setUpImageService($imageService, UserRole::REGULAR_USER);

        $admin = new AdminProfileService($validationService, $responseService, $imageService);

        $this->setUpProfileRegistry($admin, UserRole::REGULAR_USER);

        $this->setUpUserProfileService($factory, $admin, UserRole::ADMIN);
    }
}
