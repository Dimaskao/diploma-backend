<?php

namespace Services\Auth;

use App\Enums\UserRole;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use TestsEnums\Entity;
use TestsHelpers\Auth\AuthHelper;
use TestsHelpers\Auth\AuthTests;
use TestsHelpers\Auth\Login\AuthLoginTestsHelper;
use TestsHelpers\Auth\Logout\AuthLogoutTestsHelper;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;
    use AuthHelper;
    use AuthTests;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->setUpRegistry(Entity::SERVICE, UserRole::REGULAR_USER);
    }
}
