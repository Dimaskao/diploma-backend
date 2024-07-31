<?php

namespace Services\SocialNetwork;

use App\Enums\UserRole;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use TestsHelpers\SocialNetwork\Subscription\SubscriptionServiceTestsHelper;

class SubscriptionServiceTest extends TestCase
{
    use RefreshDatabase, SubscriptionServiceTestsHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->setUpSubscriptionService(UserRole::REGULAR_USER);
    }
}
