<?php

namespace App\Providers;

use App\Services\Profile\RegularUserProfileService;
use App\Strategies\Profile\RegularUserProfileStrategy;
use Illuminate\Support\ServiceProvider;

class RegularUserProfileStrategyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RegularUserProfileStrategy::class, function ($app) {
            return new RegularUserProfileStrategy(
                $app->make(RegularUserProfileService::class)
            );
        });
    }
}
