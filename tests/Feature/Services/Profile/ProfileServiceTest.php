<?php

namespace Services\Profile;

use App\Enums\UserRole;
use App\Factories\ProfileStrategyFactory;
use App\Services\Profile\SpecificProfile\Admin\AdminProfileService;
use App\Services\Profile\SpecificProfile\Company\CompanyProfileService;
use App\Services\Profile\SpecificProfile\RegularUser\RegularUserProfileService;
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
        $this->setUpUserProfileService($factory, new AdminProfileService(), UserRole::ADMIN);
    }
}
