<?php

namespace TestsHelpers\SocialNetwork\Chat;

use Illuminate\Foundation\Testing\RefreshDatabase;
use TestsHelpers\Auth\AuthHelper;
use TestsHelpers\SocialNetwork\ExpectedTestsResults;

trait ChatServiceTestsHelper
{
    use AuthHelper, RefreshDatabase, ChatServiceHelper, ExpectedTestsResults;

    public function testCreateOneToOneChat(): void
    {
        $user = $this->getTestUser();
        $response = $this->createOneToOneChat($user);
        $this->expectedCreateOneToOneChatResult($response, $user);
    }

    public function testCreateGroupChat(): void
    {
        $user = $this->getTestUser();
        $response = $this->createGroupChat($user);
        $this->expectedCreateGroupChatResult($response, $user);
    }

    public function testCreateChatFails(): void
    {
        $data = ['name' => 'Test Chat', 'user_id' => 'test id'];
        $response = $this->chatService->createChat($data);
        $this->expectedCreateChatFailedResult($response);
    }

    public function testAddUserToChat(): void
    {
        $response = $this->addUserToChat();
        $this->expectedAddUserToChatResult($response);
    }
}
