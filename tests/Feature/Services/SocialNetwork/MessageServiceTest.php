<?php

namespace ServicesTests\SocialNetwork;

use App\Events\MessageSent;
use App\Services\SocialNetwork\MessageService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Tests\TestsHelpers\Chat\ChatHelper;

class MessageServiceTest extends TestCase
{
    use RefreshDatabase, ChatHelper;

    protected MessageService $messageService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAuthService();
        $this->seed(DatabaseSeeder::class);
        $this->messageService = new MessageService();
    }

    public function testSendMessage()
    {
        Event::fake();

        $user = $this->getRegularTestUser();
        $chat = $this->getTestChat();

        $data = [
            'user_id' => $user->id,
            'chat_id' => $chat->id,
            'content' => 'Test message content'
        ];

        $response = $this->messageService->sendMessage($data);

        $responseData = $response->getData(true);

        $this->assertEquals(201, $response->status());
        $this->assertArrayHasKey('id', $responseData);

        $this->assertDatabaseHas('messages', [
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'content' => 'Test message content'
        ]);

        Event::assertDispatched(MessageSent::class, function ($event) use ($responseData) {
            return $event->message->id === $responseData['id'];
        });
    }

    public function testGetMessages()
    {
        $user = $this->getRegularTestUser();
        $chat = $this->getTestChat();

        for ($i = 0; $i < 5; $i++) {
            $this->getTestMessage($user, $chat, "{$this->getStandardTestContent()} $i");
        }

        $response = $this->messageService->getMessages($chat->id);

        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertCount(5, $responseData);
        foreach ($responseData as $message) {
            $this->assertEquals($chat->id, $message['chat_id']);
            $this->assertEquals($user->id, $message['user_id']);
        }
    }
}
