<?php

namespace Tests\Feature\TestsHelpers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Str;

trait ChatHelper
{
    use UserProfileHelper;

    protected function getTestMessage(): Message
    {
        $chat = $this->getTestChat();
        $user = $this->getRegularTestUser();
        Message::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'content' => 'Test content for test message'
        ]);
        return Message::where('chat_id', $chat->id)->first();
    }

    protected function getTestChat(): Chat
    {
        Chat::create([
            'name' => 'TestChat123',
            'is_group' => false
        ]);
        return Chat::where('name', 'TestChat123')->first();
    }
}
