<?php

namespace Tests\Feature\ControllersTests;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestsHelpers\Auth\AuthHelper;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    use AuthHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAuthController();
        $this->seed(DatabaseSeeder::class);
    }
}
