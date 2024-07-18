<?php

namespace App\Providers;

use App\Services\Response\ResponseService;
use App\Services\SocialNetwork\SubscriptionService;
use Illuminate\Support\ServiceProvider;

class SubscriptionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SubscriptionService::class, function ($app) {
            return new SubscriptionService(
                $app->make(ResponseService::class)
            );
        });
    }
}
