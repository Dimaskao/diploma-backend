<?php

namespace TestsHelpers\SocialNetwork\Chat;

use App\Enums\UserRole;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Services\Response\ResponseService;
use App\Services\SocialNetwork\ChatService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use TestsEnums\Entity;
use TestsHelpers\Auth\AuthHelper;
use TestsHelpers\Profile\Users\UsersHelper;

trait ChatServiceHelper
{
    use AuthHelper, RefreshDatabase, UsersHelper;

    protected ChatService $chatService;
    protected ResponseService $responseService;

    protected function setUpChatService($role): void
    {
        $this->setUpRegistry(Entity::SERVICE, $role);
        $this->responseService = new ResponseService();
        $this->chatService = new ChatService($this->responseService);
    }

    protected function getTestMessage(User $user, Chat $chat, string $content): Message
    {
        return Message::create([
            'id' => (string)Str::uuid(),
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'content' => $content
        ]);
    }

    protected function getStandardTestContent(): string
    {
        return 'Test content for test message';
    }

    protected function getTestChat(): Chat
    {
        return Chat::create([
            'id' => (string)Str::uuid(),
            'name' => 'TestChat123',
            'is_group' => false
        ]);
    }

    protected function createChat($user, $role = UserRole::REGULAR_USER, $isGroup = false): JsonResponse
    {
        $this->role = $role;
        return $this->chatService->createChat($this->getCorrectChatDataToCreate($user, $isGroup));
    }

    protected function createOneToOneChat($user, $role = UserRole::REGULAR_USER): JsonResponse
    {
        return $this->createChat($user, $role);
    }

    protected function createGroupChat($user, $role = UserRole::REGULAR_USER): JsonResponse
    {
        return $this->createChat($user, $role, true);
    }

    protected function getCorrectChatDataToCreate($user, $isGroup = false): array
    {
        return [
            'name' => 'Test Chat',
            'is_group' => $isGroup,
            'user_id' => $user->id
        ];
    }

    protected function getIncorrectChatDataToCreate(): array
    {
        return [
            'name' => 'Test Chat',
            'user_id' => 'test id'
        ];
    }

    protected function addUserToChat(): JsonResponse
    {
        $chat = $this->getTestChat();
        $user = $this->userTest();
        $newUser = $this->getNewTestUser();

        // Ensure the original user is in the chat
        $chat->users()->attach($user->id);
        return $this->chatService->addUserToChat($chat->id, $newUser->id);
    }

    protected function getNewTestUser()
    {
        $this->refreshCredentials();
        return $this->userTest();
    }
}
