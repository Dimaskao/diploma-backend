<?php

namespace Tests\Feature\ServicesTests;

use App\Enums\SearchType;
use App\Models\Company;
use App\Models\RegularUser;
use App\Models\User;
use App\Services\ChatService;
use App\Services\MessageService;
use App\Services\SocialNetworkService;
use App\Services\SubscriptionService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\Feature\TestsHelpers\ChatHelper;
use Tests\TestCase;

class SocialNetworkServiceTest extends TestCase
{
    use RefreshDatabase, ChatHelper;

    protected SocialNetworkService $snService;
    protected ChatService $chatService;
    protected SubscriptionService $subService;
    protected MessageService $msgService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAuthService();
        $this->seed(DatabaseSeeder::class);
        $this->subService = new SubscriptionService();
        $this->chatService = new ChatService();
        $this->msgService = new MessageService();
        $this->snService = new SocialNetworkService($this->subService, $this->chatService, $this->msgService);
    }

    public function testSearchUsers()
    {
        $user = RegularUser::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);
        User::factory()->create(['user_id' => $user->id]);
        $request = new Request(['query' => 'John', 'searchType' => SearchType::USERS]);

        $response = $this->socialNetworkService->search($request);

        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertCount(1, $responseData['results']);
        $this->assertEquals("John Doe", $responseData['results'][0]['name']);
    }

    public function testSearchCompanies()
    {
        $company = Company::factory()->create(['name' => 'Acme Corporation']);
        User::factory()->create(['company_id' => $company->id]);
        $request = new Request(['query' => 'Acme', 'searchType' => SearchType::COMPANIES]);

        $response = $this->socialNetworkService->search($request);

        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertCount(1, $responseData['results']);
        $this->assertEquals("Acme Corporation", $responseData['results'][0]['name']);
    }

    public function testSubscribe()
    {
        $this->subscriptionServiceMock->method('subscribe')->willReturn(response()->json(['message' => 'Subscribed'], 200));
        $request = new Request(['subscriber_id' => 1, 'subscribed_to_id' => 2]);

        $response = $this->socialNetworkService->subscribe($request);

        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('Subscribed', $responseData['message']);
    }

    public function testUnsubscribe()
    {
        $this->subscriptionServiceMock->method('unsubscribe')->willReturn(response()->json(['message' => 'Unsubscribed'], 200));
        $request = new Request(['subscriber_id' => 1, 'subscribed_to_id' => 2]);

        $response = $this->socialNetworkService->unsubscribe($request);

        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('Unsubscribed', $responseData['message']);
    }

    public function testCreateChat()
    {
        $this->chatServiceMock->method('createChat')->willReturn(response()->json(['message' => 'Chat created'], 201));
        $request = new Request(['name' => 'Test Chat', 'is_group' => false]);

        $response = $this->socialNetworkService->createChat($request);

        $responseData = $response->getData(true);

        $this->assertEquals(201, $response->status());
        $this->assertEquals('Chat created', $responseData['message']);
    }

    public function testAddUserToChat()
    {
        $this->chatServiceMock->method('addUserToChat')->willReturn(response()->json(['message' => 'User added to chat'], 200));
        $request = new Request(['user_id' => 1]);

        $response = $this->socialNetworkService->addUserToChat($request, 1);

        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('User added to chat', $responseData['message']);
    }

    public function testSendMessage()
    {
        $this->messageServiceMock->method('sendMessage')->willReturn(response()->json(['message' => 'Message sent'], 201));
        $request = new Request(['chat_id' => 1, 'user_id' => 1, 'content' => 'Test message']);

        $response = $this->socialNetworkService->sendMessage($request);

        $responseData = $response->getData(true);

        $this->assertEquals(201, $response->status());
        $this->assertEquals('Message sent', $responseData['message']);
    }

    public function testGetMessages()
    {
        $this->messageServiceMock->method('getMessages')->willReturn(response()->json(['messages' => ['Test message']], 200));

        $response = $this->socialNetworkService->getMessages(1);

        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('Test message', $responseData['messages'][0]);
    }
}
