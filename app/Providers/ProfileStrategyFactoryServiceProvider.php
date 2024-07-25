<?php

namespace App\Providers;

use App\Factories\ProfileStrategyFactory;
use Illuminate\Support\ServiceProvider;

class ProfileStrategyFactoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ProfileStrategyFactory::class, function ($app) {
            return new ProfileStrategyFactory();
        });
    }
}
