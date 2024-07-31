<?php

namespace TestsHelpers\SocialNetwork\Message;

use App\Services\Response\ResponseService;
use App\Services\SocialNetwork\MessageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Event;
use TestsEnums\Entity;
use TestsHelpers\Auth\AuthHelper;
use TestsHelpers\Profile\Users\UsersHelper;
use TestsHelpers\SocialNetwork\Chat\ChatServiceHelper;

trait MessageServiceHelper
{
    use AuthHelper, RefreshDatabase, UsersHelper, ChatServiceHelper;

    protected MessageService $messageService;
    protected ResponseService $responseService;

    protected function setUpMessageService($role): void
    {
        $this->setUpRegistry(Entity::SERVICE, $role);
        $this->responseService = new ResponseService();
        $this->messageService = new MessageService($this->responseService);
    }

    private function sendMessage($chat, $user): JsonResponse
    {
        Event::fake();

        $data = [
            'user_id' => $user->id,
            'chat_id' => $chat->id,
            'content' => 'Test message content'
        ];

        return $this->messageService->sendMessage($data);
    }
}
