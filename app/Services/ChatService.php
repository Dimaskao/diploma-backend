<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ChatService
{
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
                    return response()->json($chat, 201);
                }
            }
            return response()->json('Bad request', 400);
        } catch (Exception $e) {
            return response()->json("Error during creating a chat, {$e->getMessage()}", 500);
        }
    }

    public function addUserToChat($chatId, $userId): JsonResponse
    {
        $chat = Chat::findOrFail($chatId);
        $user = User::findOrFail($userId);

        $chat->users()->attach($user->id);

        return response()->json(['message' => 'User added to chat'], 200);
    }
}
