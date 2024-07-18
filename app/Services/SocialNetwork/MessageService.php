<?php

namespace App\Services\SocialNetwork;

use App\Enums\ResponseKeys;
use App\Events\MessageSent;
use App\Models\Message;
use App\Services\Response\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class MessageService
{
    protected ResponseService $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    public function sendMessage(array $data): JsonResponse
    {
        $message = Message::create([
            'id' => (string)Str::uuid(),
            'chat_id' => $data['chat_id'],
            'user_id' => $data['user_id'],
            'content' => $data['content']
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return $this->responseService->response(ResponseKeys::MESSAGE, $message, 201);
    }

    public function getMessages($chatId): JsonResponse
    {
        $messages = Message::where('chat_id', $chatId)->get();
        return $this->responseService->response(ResponseKeys::MESSAGE, $messages, 200);
    }
}
