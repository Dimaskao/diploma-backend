<?php

namespace App\Providers;

use App\Services\Response\ResponseService;
use App\Services\SocialNetwork\ChatService;
use App\Services\SocialNetwork\MessageService;
use App\Services\SocialNetwork\SearchService;
use App\Services\SocialNetwork\SocialNetworkService;
use App\Services\SocialNetwork\SubscriptionService;
use Illuminate\Support\ServiceProvider;

class SocialNetworkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SocialNetworkService::class, function ($app) {
            return new SocialNetworkService(
                $app->make(SubscriptionService::class),
                $app->make(ChatService::class),
                $app->make(MessageService::class),
                $app->make(SearchService::class),
                $app->make(ResponseService::class)
            );
        });
    }
}
