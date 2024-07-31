<?php

namespace App\Providers;

use App\Services\Profile\SpecificProfile\Company\CompanyProfileService;
use App\Strategies\Profile\SpecificProfile\CompanyProfileStrategy;
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
