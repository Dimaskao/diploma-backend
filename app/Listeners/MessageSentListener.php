<?php

namespace App\Listeners;

use App\Events\MessageSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class MessageSentListener
{
    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        // Handle the event logic, e.g., log the message
        Log::info('Message sent: ' . $event->message->content);
    }
}
