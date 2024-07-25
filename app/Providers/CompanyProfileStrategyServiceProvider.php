<?php

namespace App\Providers;

use App\Services\Profile\CompanyProfileService;
use App\Strategies\Profile\CompanyProfileStrategy;
use Illuminate\Support\ServiceProvider;

class CompanyProfileStrategyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CompanyProfileStrategy::class, function ($app) {
            return new CompanyProfileStrategy(
                $app->make(CompanyProfileService::class)
            );
        });
    }
}
