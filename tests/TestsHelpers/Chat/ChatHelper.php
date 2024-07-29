<?php

namespace Tests\TestsHelpers\Chat;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Str;
use TestsHelpers\Profile\Users\UsersHelper;

trait ChatHelper
{
    use UsersHelper;

    protected function getTestMessage(User $user, Chat $chat, string $content): Message
    {
//        Log::debug('$chat: ' . var_export($chat, 1));
//        Log::debug('$user: ' . var_export($user, 1));

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
}
