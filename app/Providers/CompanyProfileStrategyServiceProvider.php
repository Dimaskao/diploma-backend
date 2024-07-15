<?php

namespace App\Providers;

use App\Strategies\CompanyProfileStrategy;
use App\Services\CompanyProfileService;
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
