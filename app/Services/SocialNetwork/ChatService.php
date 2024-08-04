<?php

namespace App\Services\SocialNetwork;

use App\Enums\ResponseKey;
use App\Models\Chat;
use App\Models\User;
use App\Services\Response\ResponseService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ChatService
{
    protected ResponseService $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    public function createChat(array $data): JsonResponse
    {
        try {
            if (isset($data['name']) && isset($data['is_group']) && isset($data['user_id'])) {
                $chat = Chat::create([
                    'id' => (string)Str::uuid(),
                    'name' => $data['name'],
                    'is_group' => $data['is_group'],
                ]);

                $user = User::find($data['user_id']);
                if ($user) {
                    $chat->users()->attach($data['user_id']);
                    return $this->responseService->created($chat);
                }
            }
            return $this->responseService->badRequest();
        } catch (Exception $e) {
            return $this->responseService->internalServerError("Error during creating a chat, {$e->getMessage()}");
        }
    }

    public function addUserToChat($chatId, $userId): JsonResponse
    {
        $chat = Chat::findOrFail($chatId);
        $user = User::findOrFail($userId);

        $chat->users()->attach($user->id);
        return $this->responseService->success();
    }
}
