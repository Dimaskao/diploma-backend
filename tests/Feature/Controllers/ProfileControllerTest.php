<?php

namespace Controllers;

use App\Enums\UserRole;
use App\Services\Profile\SpecificProfile\Admin\AdminProfileService;
use App\Services\Profile\SpecificProfile\Company\CompanyProfileService;
use App\Services\Profile\SpecificProfile\RegularUser\RegularUserProfileService;
use App\Strategies\Profile\SpecificProfile\AdminProfileStrategy;
use App\Strategies\Profile\SpecificProfile\CompanyProfileStrategy;
use App\Strategies\Profile\SpecificProfile\RegularUserProfileStrategy;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;
use TestsHelpers\Controllers\Profile\ProfileControllerTestsHelper;

class ProfileControllerTest extends TestCase
{
    use ProfileControllerTestsHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

//        $this->setUpRegularUserProfileController();
//        $this->setUpCompanyProfileController();
        $this->setUpAdminProfileController();
    }

    private function setUpRegularUserProfileController(): void
    {
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
