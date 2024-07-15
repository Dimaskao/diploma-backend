<?php

namespace Tests\Feature\ServicesTests;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Services\MessageService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\Feature\TestsHelpers\ChatHelper;
use Tests\TestCase;

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

        $this->authRegularUser();

        $data = [
            'user_id' => $user->id,
            'chat_id' => $chat->id,
            'content' => 'Test message content'
        ];

        $response = $this->messageService->sendMessage($data);

        $response->assertStatus(201);
        $responseData = $response->json();

        $this->assertDatabaseHas('messages', [
            'id' => $responseData['id'],
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'content' => 'Test message content'
        ]);

        Event::assertDispatched(MessageSent::class, function ($event) use ($responseData) {
            return $event->message->id === $responseData['id'];
        });
    }

//    public function testGetMessages()
//    {
//        $user = User::factory()->create();
//        $chat = Chat::create([
//            'id' => (string)Str::uuid(),
//            'name' => 'TestChat123',
//            'is_group' => false
//        ]);
//
//        $messages = Message::factory()->count(5)->create([
//            'chat_id' => $chat->id,
//            'user_id' => $user->id,
//            'content' => 'Test message content'
//        ]);
//
//        $response = $this->messageService->getMessages($chat->id);
//
//        $response->assertStatus(200);
//        $responseData = $response->json();
//
//        $this->assertCount(5, $responseData);
//        foreach ($responseData as $message) {
//            $this->assertEquals('Test message content', $message['content']);
//            $this->assertEquals($chat->id, $message['chat_id']);
//            $this->assertEquals($user->id, $message['user_id']);
//        }
//    }
}
