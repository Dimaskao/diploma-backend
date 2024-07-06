<?php

namespace App\Providers;

use App\Factories\ProfileStrategyFactory;
use App\Factories\UserFactory;
use App\Services\AuthService;
use App\Services\ProfileService;
use App\Services\SubscriptionService;
use App\Services\ValidationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ProfileStrategyFactory::class, function ($app) {
            return new ProfileStrategyFactory();
        });

        $this->app->singleton(UserFactory::class, function ($app) {
            return new UserFactory();
        });

        $this->app->singleton(SubscriptionService::class, function ($app) {
            return new SubscriptionService();
        });

        $this->app->singleton(ValidationService::class, function ($app) {
            return new ValidationService();
        });

        $this->app->singleton(AuthService::class, function ($app) {
            return new AuthService(
                $app->make(ValidationService::class),
                $app->make(UserFactory::class)
            );
        });

        $this->app->singleton(ProfileService::class, function ($app) {
            return new ProfileService(
                $app->make(ProfileStrategyFactory::class),
                $app->make(SubscriptionService::class)
            );
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
