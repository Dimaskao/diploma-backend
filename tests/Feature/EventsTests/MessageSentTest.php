<?php

namespace Tests\Feature\EventsTests;

use App\Events\MessageSent;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\Feature\TestsHelpers\ChatHelper;
use Tests\TestCase;

class MessageSentTest extends TestCase
{
    use ChatHelper, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAuthService();
        $this->seed(DatabaseSeeder::class);
    }

    public function testMessageSentEvent()
    {
        Event::fake();

        $chat = $this->getTestChat();
        $user = $this->getRegularTestUser();

        $message = $this->getTestMessage($user, $chat, $this->getStandardTestContent());
        event(new MessageSent($message));

        Event::assertDispatched(MessageSent::class, function ($event) use ($message) {
            return $event->message->id === $message->id;
        });
    }

    public function testBroadcastingChannel()
    {
        $chat = $this->getTestChat();
        $user = $this->getRegularTestUser();

        $message = $this->getTestMessage($user, $chat, $this->getStandardTestContent());
        $event = new MessageSent($message);

        $this->assertEquals('private-chat.' . $message->chat_id, $event->broadcastOn()->name);
    }
}
