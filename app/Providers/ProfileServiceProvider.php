<?php

namespace App\Providers;

use App\Services\ProfileService;
use App\Factories\ProfileStrategyFactory;
use Illuminate\Support\ServiceProvider;

class ProfileServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ProfileService::class, function ($app) {
            return new ProfileService(
                $app->make(ProfileStrategyFactory::class)
            );
        });
    }
}
