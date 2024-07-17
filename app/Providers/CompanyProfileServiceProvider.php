<?php

namespace App\Providers;

use App\Services\Profile\CompanyProfileService;
use Illuminate\Support\ServiceProvider;

class CompanyProfileServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CompanyProfileService::class, function ($app) {
            return new CompanyProfileService();
        });
    }
}
