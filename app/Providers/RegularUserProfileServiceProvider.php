<?php

namespace App\Providers;

use App\Services\Profile\SpecificProfile\RegularUser\RegularUserProfileService;
use Illuminate\Support\ServiceProvider;

class RegularUserProfileServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RegularUserProfileService::class, function ($app) {
            return new RegularUserProfileService();
        });
    }
}
