<?php

namespace Tests\Feature\ServicesTests;

use App\Enums\SearchType;
use App\Services\SearchService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tests\Feature\TestsHelpers\ChatHelper;
use Tests\TestCase;

class SearchServiceTest extends TestCase
{
    use RefreshDatabase, ChatHelper;

    protected SearchService $searchService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpAuthService();
        $this->seed(DatabaseSeeder::class);
        $this->searchService = new SearchService();
    }

    public function testSearchUsers()
    {
        $this->getRegularTestUser();
        $request = new Request(['query' => 'John', 'searchType' => SearchType::USERS]);

        $response = $this->searchService->search($request);

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

        $response = $this->searchService->search($request);

        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertEquals("Test Company", $responseData['results']['companies'][0]['name']);
    }

//    public function testSubscribe()
//    {
//        $this->subscriptionServiceMock->method('subscribe')->willReturn(response()->json(['message' => 'Subscribed'], 200));
//        $request = new Request(['subscriber_id' => 1, 'subscribed_to_id' => 2]);
//
//        $response = $this->socialNetworkService->subscribe($request);
//
//        $responseData = $response->getData(true);
//
//        $this->assertEquals(200, $response->status());
//        $this->assertEquals('Subscribed', $responseData['message']);
//    }
//
//    public function testUnsubscribe()
//    {
//        $this->subscriptionServiceMock->method('unsubscribe')->willReturn(response()->json(['message' => 'Unsubscribed'], 200));
//        $request = new Request(['subscriber_id' => 1, 'subscribed_to_id' => 2]);
//
//        $response = $this->socialNetworkService->unsubscribe($request);
//
//        $responseData = $response->getData(true);
//
//        $this->assertEquals(200, $response->status());
//        $this->assertEquals('Unsubscribed', $responseData['message']);
//    }
//
//    public function testCreateChat()
//    {
//        $this->chatServiceMock->method('createChat')->willReturn(response()->json(['message' => 'Chat created'], 201));
//        $request = new Request(['name' => 'Test Chat', 'is_group' => false]);
//
//        $response = $this->socialNetworkService->createChat($request);
//
//        $responseData = $response->getData(true);
//
//        $this->assertEquals(201, $response->status());
//        $this->assertEquals('Chat created', $responseData['message']);
//    }
//
//    public function testAddUserToChat()
//    {
//        $this->chatServiceMock->method('addUserToChat')->willReturn(response()->json(['message' => 'User added to chat'], 200));
//        $request = new Request(['user_id' => 1]);
//
//        $response = $this->socialNetworkService->addUserToChat($request, 1);
//
//        $responseData = $response->getData(true);
//
//        $this->assertEquals(200, $response->status());
//        $this->assertEquals('User added to chat', $responseData['message']);
//    }
//
//    public function testSendMessage()
//    {
//        $this->messageServiceMock->method('sendMessage')->willReturn(response()->json(['message' => 'Message sent'], 201));
//        $request = new Request(['chat_id' => 1, 'user_id' => 1, 'content' => 'Test message']);
//
//        $response = $this->socialNetworkService->sendMessage($request);
//
//        $responseData = $response->getData(true);
//
//        $this->assertEquals(201, $response->status());
//        $this->assertEquals('Message sent', $responseData['message']);
//    }
//
//    public function testGetMessages()
//    {
//        $this->messageServiceMock->method('getMessages')->willReturn(response()->json(['messages' => ['Test message']], 200));
//
//        $response = $this->socialNetworkService->getMessages(1);
//
//        $responseData = $response->getData(true);
//
//        $this->assertEquals(200, $response->status());
//        $this->assertEquals('Test message', $responseData['messages'][0]);
//    }
}
