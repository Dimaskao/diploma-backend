<?php

namespace Services\SocialNetwork;

use App\Enums\UserRole;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use TestsHelpers\SocialNetwork\SocialNetwork\SocialNetworkServiceTestsHelper;

class SocialNetworkServiceTest extends TestCase
{
    use RefreshDatabase, SocialNetworkServiceTestsHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->setUpSocialNetworkService(UserRole::REGULAR_USER);
    }
}
