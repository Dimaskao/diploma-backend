<?php

namespace Services\Profile\SpecificProfile;

use App\Enums\UserRole;
use App\Services\Profile\SpecificProfile\RegularUser\RegularUserProfileService;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;
use Tests\TestsHelpers\Profile\ProfileServiceTestsHelper;

class RegularUserProfileServiceTest extends TestCase
{
    use ProfileServiceTestsHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->setUpProfileRegistry(new RegularUserProfileService(), UserRole::REGULAR_USER);
    }
}
