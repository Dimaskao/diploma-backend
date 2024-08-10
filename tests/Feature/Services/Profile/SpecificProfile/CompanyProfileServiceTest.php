<?php

namespace Services\Profile\SpecificProfile;

use App\Enums\UserRole;
use App\Services\Image\ImageProcessingService;
use App\Services\Image\ImageUploadService;
use App\Services\Profile\SpecificProfile\Company\CompanyProfileService;
use App\Services\Response\ResponseService;
use App\Services\Validation\ValidationService;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;
use Tests\TestsHelpers\Profile\ProfileServiceTestsHelper;

class CompanyProfileServiceTest extends TestCase
{
    use ProfileServiceTestsHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $validationService = new ValidationService();
        $responseService = new ResponseService();
        $imageService = new ImageUploadService(new ImageProcessingService());

        $this->setUpImageService($imageService, UserRole::REGULAR_USER);

        $this->setUpProfileRegistry(new CompanyProfileService($validationService, $responseService, $imageService), UserRole::COMPANY);
    }
}
