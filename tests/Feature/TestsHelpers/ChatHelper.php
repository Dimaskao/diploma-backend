<?php

namespace Tests\Feature\TestsHelpers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait ChatHelper
{
    use UserProfileHelper;

    protected function getTestMessage(): Message
    {
        $chat = $this->getTestChat();
        $user = $this->getRegularTestUser();
//        Log::debug('$chat: ' . var_export($chat, 1));
//        Log::debug('$user: ' . var_export($user, 1));

        return Message::create([
            'id' => (string)Str::uuid(),
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'content' => 'Test content for test message'
        ]);
    }

    protected function getTestChat(): Chat
    {
        return Chat::create([
            'id' => (string)Str::uuid(),
            'name' => 'TestChat123',
            'is_group' => false
        ]);
    }
}
