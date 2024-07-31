<?php

namespace Services\SocialNetwork;

use App\Enums\UserRole;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;
use TestsHelpers\SocialNetwork\Chat\ChatServiceTestsHelper;

class ChatServiceTest extends TestCase
{
    use ChatServiceTestsHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->setUpChatService(UserRole::REGULAR_USER);
    }
}
