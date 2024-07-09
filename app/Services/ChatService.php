<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class ChatService
{
    public function createChat(array $data): JsonResponse
    {
        $chat = Chat::create([
            'name' => $data['name'],
            'is_group' => $data['is_group'],
        ]);

        $chat->users()->attach(Auth::id());

        return response()->json($chat, 201);
    }

    public function addUserToChat(int $chatId, int $userId): JsonResponse
    {
        $chat = Chat::findOrFail($chatId);
        $user = User::findOrFail($userId);

        $chat->users()->attach($user->id);

        return response()->json(['message' => 'User added to chat'], 200);
    }
}
