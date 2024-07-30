<?php

namespace App\Providers;

use App\Services\Profile\SpecificProfile\RegularUser\RegularUserProfileService;
use App\Strategies\Profile\SpecificProfile\RegularUserProfileStrategy;
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
