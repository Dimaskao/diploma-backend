<?php

namespace Services\SocialNetwork;

use App\Enums\UserRole;
use App\Services\SocialNetwork\MessageService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use TestsHelpers\SocialNetwork\Message\MessageServiceTestsHelper;

class MessageServiceTest extends TestCase
{
    use RefreshDatabase, MessageServiceTestsHelper;

    protected MessageService $messageService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->setUpMessageService(UserRole::REGULAR_USER);
    }
}
