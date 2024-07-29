<?php

namespace Services\Auth;

use App\Enums\UserRole;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use TestsEnums\Entity;
use TestsHelpers\Auth\AuthHelper;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;
    use AuthHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->setUpRegistry(Entity::SERVICE, UserRole::REGULAR_USER);
    }
}
