<?php

namespace App\Providers;

use App\Services\Response\ResponseService;
use App\Services\SocialNetwork\ChatService;
use Illuminate\Support\ServiceProvider;

class ChatServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ChatService::class, function ($app) {
            return new ChatService(
                $app->make(ResponseService::class)
            );
        });
    }
}
