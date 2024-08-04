<?php

namespace TestsHelpers\SocialNetwork;

use App\Events\MessageSent;
use App\Models\User;
use Illuminate\Support\Facades\Event;

trait ExpectedTestsResults
{
    private function expectedCreateOneToOneChatResult($response, $user): void
    {
        $responseData = $response->getData(true);

        $this->assertEquals(201, $response->status());
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('id', $responseData['data']);
        $this->assertEquals('Test Chat', $responseData['data']['name']);
        $this->assertFalse($responseData['data']['is_group']);

        $chatData = $this->getCorrectChatDataToCreate($user);
        $this->assertDatabaseHas('chats', [
            'id' => $responseData['data']['id'],
            'name' => $chatData['name'],
            'is_group' => false,
        ]);

        $this->assertDatabaseHas('chat_user', [
            'chat_id' => $responseData['data']['id'],
            'user_id' => $user->id,
        ]);
    }

    private function expectedCreateGroupChatResult($response, $user): void
    {
        $responseData = $response->getData(true);

        $this->assertEquals(201, $response->status());
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('id', $responseData['data']);
        $this->assertEquals('Test Chat', $responseData['data']['name']);
        $this->assertTrue($responseData['data']['is_group']);

        $chatData = $this->getCorrectChatDataToCreate($user);
        $this->assertDatabaseHas('chats', [
            'id' => $responseData['data']['id'],
            'name' => $chatData['name'],
            'is_group' => true,
        ]);

        $this->assertDatabaseHas('chat_user', [
            'chat_id' => $responseData['data']['id'],
            'user_id' => $user->id,
        ]);
    }

    private function expectedCreateChatFailedResult($response): void
    {
        $this->assertEquals(400, $response->status());
    }

    private function expectedAddUserToChatResult($response): void
    {
        $responseData = $response->getData(true);
        $this->assertEquals(200, $response->status());
        $this->assertEquals('Success', $responseData['message']);
    }

    private function expectedSendMessageResult($response, $chat, $user): void
    {
        $responseData = $response->getData(true);

        $this->assertEquals(201, $response->status());
        $this->assertArrayHasKey('id', $responseData['data']);

        $this->assertDatabaseHas('messages', [
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'content' => 'Test message content'
        ]);

        Event::assertDispatched(MessageSent::class, function ($event) use ($responseData) {
            return $event->message->id === $responseData['data']['id'];
        });
    }

    private function expectedGetMessagesResult($response, $chat, $user): void
    {
        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertCount(5, $responseData['data']);
        foreach ($responseData['data'] as $message) {
            $this->assertEquals($chat->id, $message['chat_id']);
            $this->assertEquals($user->id, $message['user_id']);
        }
    }

    private function expectedSearchUsersResult($response): void
    {
        $responseData = $response->getData(true);;

        $this->assertEquals(200, $response->status());
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Success', $responseData['message']);
        $this->assertEquals("John Doe", $responseData['data']['users'][0]['name']);
    }

    private function expectedSearchCompaniesResult($response): void
    {
        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Success', $responseData['message']);
        $this->assertEquals("Test Company", $responseData['data']['companies'][0]['name']);
    }

    private function expectedSubscribeUserSuccess($response, $subscriberId, $subscriptionId): void
    {
        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Success', $responseData['message']);

        $this->assertDatabaseHas('user_contacts', [
            'subscriber_id' => User::find($subscriberId)->userProfile->regular_user_id,
            'subscription_id' => $subscriptionId
        ]);
    }

    private function expectedSubscribeUserBadRequestResult($response): void
    {
        $responseData = $response->getData(true);

        $this->assertEquals(400, $response->status());
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Subscription ID or Subscriber ID was not set', $responseData['message']);
    }

    private function expectedUnsubscribeUserSuccessResult($response): void
    {
        $responseData = $response->getData(true);

        $this->assertEquals(200, $response->status());
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Success', $responseData['message']);
    }

    private function expectedUnsubscribeUserNotFoundResult($response): void
    {
        $responseData = $response->getData(true);

        $this->assertEquals(404, $response->status());
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Subscription not found', $responseData['message']);
    }
}
