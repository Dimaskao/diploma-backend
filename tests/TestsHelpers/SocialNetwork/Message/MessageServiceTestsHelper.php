<?php

namespace TestsHelpers\SocialNetwork\Message;

use TestsHelpers\SocialNetwork\ExpectedTestsResults;

trait MessageServiceTestsHelper
{
    use MessageServiceHelper, ExpectedTestsResults;

    public function testSendMessage(): void
    {
        $user = $this->userTest();
        $chat = $this->getTestChat();
        $response = $this->sendMessage($chat, $user);
        $this->expectedSendMessageResult($response, $chat, $user);
    }

    public function testGetMessages(): void
    {
        $chat = $this->getTestChat();

        for ($i = 0; $i < 5; $i++) {
            $user = $this->userTest();
            $this->getTestMessage($user, $chat, "{$this->getStandardTestContent()} $i");
        }

        $response = $this->messageService->getMessages($chat->id);
        $this->expectedGetMessagesResult($response, $chat, $user);
    }
}
