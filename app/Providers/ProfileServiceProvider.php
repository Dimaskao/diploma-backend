<?php

namespace App\Providers;

use App\Factories\ProfileStrategyFactory;
use App\Services\Profile\UserProfileService;
use Illuminate\Support\ServiceProvider;

class ProfileServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(UserProfileService::class, function ($app) {
            return new UserProfileService(
                $app->make(ProfileStrategyFactory::class)
            );
        });
    }
}
