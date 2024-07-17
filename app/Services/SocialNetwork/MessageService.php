<?php

namespace App\Services\SocialNetwork;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class MessageService
{
    public function sendMessage(array $data): JsonResponse
    {
        $message = Message::create([
            'id' => (string)Str::uuid(),
            'chat_id' => $data['chat_id'],
            'user_id' => $data['user_id'],
            'content' => $data['content']
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message, 201);
    }

    public function getMessages($chatId): JsonResponse
    {
        $messages = Message::where('chat_id', $chatId)->get();
        return response()->json($messages);
    }
}
