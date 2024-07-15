<?php

namespace App\Providers;

use App\Services\ChatService;
use App\Services\MessageService;
use App\Services\SearchService;
use App\Services\SocialNetworkService;
use App\Services\SubscriptionService;
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
                $app->make(SearchService::class)
            );
        });
    }
}
