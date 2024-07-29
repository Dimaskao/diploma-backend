<?php

namespace ServicesTests\SocialNetwork;

use App\Enums\SearchType;
use App\Models\RegularUser;
use App\Services\SocialNetwork\ChatService;
use App\Services\SocialNetwork\MessageService;
use App\Services\SocialNetwork\SearchService;
use App\Services\SocialNetwork\SocialNetworkService;
use App\Services\SocialNetwork\SubscriptionService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Tests\TestsHelpers\Chat\ChatHelper;

class SocialNetworkServiceTest extends TestCase
{
    use RefreshDatabase, ChatHelper;

    protected SocialNetworkService $snService;
    protected ChatService $chatService;
    protected SubscriptionService $subService;
    protected MessageService $msgService;
    protected SearchService $searchService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAuthService();
        $this->seed(DatabaseSeeder::class);
        $this->subService = new SubscriptionService();
        $this->chatService = new ChatService();
        $this->msgService = new MessageService();
        $this->searchService = new SearchService();
        $this->snService = new SocialNetworkService($this->subService, $this->chatService, $this->msgService, $this->searchService);
    }

    public function testSearchUsers()
    {
        $this->getRegularTestUser();
        $request = new Request(['query' => 'John', 'searchType' => SearchType::USERS]);

        $response = $this->snService->search($request);

        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertCount(2, $responseData['results']);
        Log::debug('$responseData[results]: ' . var_export($responseData['results'], 1));
        $this->assertEquals("John Doe", $responseData['results']['users'][0]['name']);
    }

    public function testSearchCompanies()
    {
        $this->getCompanyTestUser();
        $request = new Request(['query' => 'Test Company', 'searchType' => SearchType::COMPANIES]);

        $response = $this->snService->search($request);

        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertEquals("Test Company", $responseData['results']['companies'][0]['name']);
    }

    public function testSubscribeUserSuccess()
    {
        list($subscriber, $subscription, $response) = $this->subscribe($this->snService);

        $this->validateJsonResponse($response, 200, ['message' => 'Subscribed successfully']);
        $this->assertDatabaseHas('user_contacts', [
            'subscriber_id' => RegularUser::where('id', $subscriber->user_id)->first()->id,
            'subscription_id' => $subscription->id
        ]);
    }

    public function testSubscribeUserBadRequest()
    {
        $data = []; // Empty data to simulate bad request
        $request = new Request($data);
        $response = $this->snService->subscribe($request);

        $this->validateJsonResponse($response, 400, ['message' => 'Bad request']);
    }

    public function testUnsubscribeUserSuccess()
    {
        list($subscriber, $subscription, $response) = $this->subscribe($this->snService);

        $data = [
            'subscriberId' => $subscriber->id,
            'subscriptionId' => $subscription->id,
        ];

        $request = new Request($data);
        $response = $this->snService->unsubscribe($request);

        $this->validateJsonResponse($response, 200, ['message' => 'Unsubscribed successfully']);
    }

    public function testUnsubscribeUserNotFound()
    {
        $subscriber = $this->getRegularTestUser();
        $subscription = $this->getCompanyTestUser();

        $data = [
            'subscriberId' => $subscriber->id,
            'subscriptionId' => $subscription->id,
        ];

        $request = new Request($data);
        $response = $this->snService->unsubscribe($request);

        $this->validateJsonResponse($response, 404, ['message' => 'Subscription not found']);
    }

    public function testCreateOneToOneChat()
    {
        $user = $this->getRegularTestUser();

        $data = [
            'name' => 'Test Chat',
            'is_group' => false,
            'user_id' => $user->id
        ];

        $request = new Request($data);
        $response = $this->snService->createChat($request);

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

        $request = new Request($data);
        $response = $this->snService->createChat($request);

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

        $request = new Request($data);
        $response = $this->snService->createChat($request);

        $this->assertEquals(400, $response->status());
    }

    public function testAddUserToChat()
    {
        $chat = $this->getTestChat();
        $user = $this->getRegularTestUser();
        $newUser = $this->getCompanyTestUser();

        // Ensure the original user is in the chat
        $chat->users()->attach($user->id);

        $data = [
            'chat_id' => $chat->id,
            'user_id' => $newUser->id
        ];

        $request = new Request($data);
        $response = $this->snService->addUserToChat($request);
        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertEquals('User added to chat', $responseData['message']);

        $this->assertDatabaseHas('chat_user', [
            'chat_id' => $chat->id,
            'user_id' => $newUser->id,
        ]);
    }
}
