<?php

namespace App\Services\SocialNetwork;

use App\Enums\ResponseKeys;
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
                    return $this->responseService->response(ResponseKeys::RESULT, $chat, 201);
                }
            }
            return $this->responseService->response(ResponseKeys::ERROR, 'Bad request', 400);
        } catch (Exception $e) {
            return $this->responseService->response(ResponseKeys::ERROR, "Error during creating a chat, {$e->getMessage()}", 500);
        }
    }

    public function addUserToChat($chatId, $userId): JsonResponse
    {
        $chat = Chat::findOrFail($chatId);
        $user = User::findOrFail($userId);

        $chat->users()->attach($user->id);
        return $this->responseService->response(ResponseKeys::MESSAGE, 'User added to chat', 200);
    }
}
