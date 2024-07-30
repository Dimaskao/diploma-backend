<?php

namespace Controllers;

use App\Enums\UserRole;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use TestsEnums\Entity;
use TestsHelpers\Auth\AuthHelper;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase, AuthHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
//        $this->setUpRegistry(Entity::CONTROLLER, UserRole::REGULAR_USER);
//        $this->setUpRegistry(Entity::CONTROLLER, UserRole::COMPANY);
        $this->setUpRegistry(Entity::CONTROLLER, UserRole::ADMIN);
    }
}
