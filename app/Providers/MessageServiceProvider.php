<?php

namespace App\Providers;

use App\Services\SocialNetwork\MessageService;
use Illuminate\Support\ServiceProvider;

class MessageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(MessageService::class, function ($app) {
            return new MessageService();
        });
    }
}
