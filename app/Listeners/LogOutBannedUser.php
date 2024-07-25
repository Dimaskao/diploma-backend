<?php

namespace App\Listeners;

use App\Events\UserBanned;

class LogOutBannedUser
{
    /**
     * Handle the event.
     *
     * @param  UserBanned  $event
     * @return void
     */
    public function handle(UserBanned $event)
    {
        // Revoke all tokens
        $event->user->tokens()->update(['revoked' => true]);
    }
}
