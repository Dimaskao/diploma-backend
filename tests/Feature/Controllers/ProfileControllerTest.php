<?php

namespace Controllers;

use App\Enums\UserRole;
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
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;
use TestsHelpers\Controllers\Profile\ProfileControllerTestsHelper;

class ProfileControllerTest extends TestCase
{
    use ProfileControllerTestsHelper;

    protected ValidationService $validationService;
    protected ResponseService $responseService;
    protected mixed $imageService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->validationService = new ValidationService();
        $this->responseService = new ResponseService();
        $this->imageService = new ImageUploadService(new ImageProcessingService());

//        $this->setUpRegularUserProfileController();
//        $this->setUpCompanyProfileController();
        $this->setUpAdminProfileController();
    }

    private function setUpRegularUserProfileController(): void
    {
        $this->setUpImageService($this->imageService, UserRole::REGULAR_USER);

        $admin = new AdminProfileService($this->validationService, $this->responseService, $this->imageService);

        $this->setUpProfileRegistry($admin, UserRole::REGULAR_USER);


        $this->setUpUserProfileService($factory, $admin, UserRole::ADMIN);
        $service = new RegularUserProfileService();
        $strategy = new RegularUserProfileStrategy($service);
        $role = UserRole::REGULAR_USER;
        $this->setUpProfileController($strategy, $service, $role);
    }

    private function setUpCompanyProfileController(): void
    {
        $service = new CompanyProfileService();
        $strategy = new CompanyProfileStrategy($service);
        $role = UserRole::COMPANY;
        $this->setUpProfileController($strategy, $service, $role);
    }

    private function setUpAdminProfileController(): void
    {
        $service = new AdminProfileService();
        $strategy = new AdminProfileStrategy($service);
        $role = UserRole::ADMIN;
        $this->setUpProfileController($strategy, $service, $role);
    }
}
