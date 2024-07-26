<?php

namespace ServicesTests\Profile;

use App\Enums\UserRole;
use App\Services\Profile\RegularUserProfileService;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;
use Tests\TestsHelpers\Profile\ProfileServiceTestsHelper;

class RegularUserProfileServiceTest extends TestCase
{
    use ProfileServiceTestsHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpProfileRegistry(new RegularUserProfileService(), UserRole::REGULAR_USER);
        $this->seed(DatabaseSeeder::class);
    }
}
