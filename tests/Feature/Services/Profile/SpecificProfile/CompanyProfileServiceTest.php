<?php

namespace Services\Profile\SpecificProfile;

use App\Enums\UserRole;
use App\Services\Profile\SpecificProfile\Company\CompanyProfileService;
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
        $this->setUpProfileRegistry(new CompanyProfileService(), UserRole::COMPANY);
    }
}
