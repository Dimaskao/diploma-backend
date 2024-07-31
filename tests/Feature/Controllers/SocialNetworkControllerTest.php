<?php

namespace Controllers;

use App\Enums\UserRole;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use TestsHelpers\Controllers\SocialNetwork\SocialNetworkControllerHelper;

class SocialNetworkControllerTest extends TestCase
{
    use SocialNetworkControllerHelper, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $role = UserRole::REGULAR_USER;
        $this->setUpSocialNetworkController($role);
    }
}
