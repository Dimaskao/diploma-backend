<?php

namespace ServicesTests\SocialNetwork;

use App\Services\SocialNetwork\ChatService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestsHelpers\Chat\ChatHelper;

class ChatServiceTest extends TestCase
{
    use RefreshDatabase, ChatHelper;

    protected ChatService $chatService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAuthService();
        $this->seed(DatabaseSeeder::class);
        $this->chatService = new ChatService();
    }

    public function testCreateOneToOneChat()
    {
        $user = $this->getRegularTestUser();

        $data = [
            'name' => 'Test Chat',
            'is_group' => false,
            'user_id' => $user->id
        ];

        $response = $this->chatService->createChat($data);

        $responseData = $response->getData(true);

        $this->assertEquals(201, $response->status());
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals('Test Chat', $responseData['name']);
        $this->assertFalse($responseData['is_group']);

        $this->assertDatabaseHas('chats', [
            'id' => $responseData['id'],
            'name' => 'Test Chat',
            'is_group' => false,
        ]);

        $this->assertDatabaseHas('chat_user', [
            'chat_id' => $responseData['id'],
            'user_id' => $user->id,
        ]);
    }

    public function testCreateGroupChat()
    {
        $user = $this->getRegularTestUser();

        $data = [
            'name' => 'Test Chat',
            'is_group' => true,
            'user_id' => $user->id
        ];

        $response = $this->chatService->createChat($data);

        $responseData = $response->getData(true);

        $this->assertEquals(201, $response->status());
        $this->assertArrayHasKey('id', $responseData);
        $this->assertEquals('Test Chat', $responseData['name']);
        $this->assertTrue($responseData['is_group']);

        $this->assertDatabaseHas('chats', [
            'id' => $responseData['id'],
            'name' => 'Test Chat',
            'is_group' => true,
        ]);

        $this->assertDatabaseHas('chat_user', [
            'chat_id' => $responseData['id'],
            'user_id' => $user->id,
        ]);
    }

    public function testCreateChatFails()
    {
        $data = [
            'name' => 'Test Chat',
            'user_id' => 'test id'
        ];

        $response = $this->chatService->createChat($data);

        $this->assertEquals(400, $response->status());
    }

    public function testAddUserToChat()
    {
        $chat = $this->getTestChat();
        $user = $this->getRegularTestUser();
        $newUser = $this->getCompanyTestUser();

        // Ensure the original user is in the chat
        $chat->users()->attach($user->id);

        $response = $this->chatService->addUserToChat($chat->id, $newUser->id);

        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('User added to chat', $responseData['message']);

        $this->assertDatabaseHas('chat_user', [
            'chat_id' => $chat->id,
            'user_id' => $newUser->id,
        ]);
    }
}
