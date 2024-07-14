<?php

namespace Tests\Feature\ServicesTests;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\TestsHelpers\AuthHelper;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;
    use AuthHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAuthService();
        $this->seed(DatabaseSeeder::class);
    }
}
