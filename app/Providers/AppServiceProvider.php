<?php

namespace App\Providers;

use App\Factories\ProfileStrategyFactory;
use App\Factories\UserFactory;
use App\Services\AuthService;
use App\Services\CompanyProfileService;
use App\Services\ProfileService;
use App\Services\RegularUserProfileService;
use App\Services\SocialNetworksService;
use App\Services\SubscriptionService;
use App\Services\ValidationService;
use App\Strategies\CompanyProfileStrategy;
use App\Strategies\RegularUserProfileStrategy;
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

        $this->app->singleton(RegularUserProfileService::class, function ($app) {
            return new RegularUserProfileService();
        });

        $this->app->singleton(CompanyProfileService::class, function ($app) {
            return new CompanyProfileService();
        });

        $this->app->singleton(SocialNetworksService::class, function ($app) {
            return new SocialNetworksService(
                $app->make(SubscriptionService::class)
            );
        });

        $this->app->bind(CompanyProfileStrategy::class, function ($app) {
            return new CompanyProfileStrategy(
                $app->make(CompanyProfileService::class)
            );
        });

        $this->app->bind(RegularUserProfileStrategy::class, function ($app) {
            return new RegularUserProfileStrategy(
                $app->make(RegularUserProfileService::class)
            );
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
