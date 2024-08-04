<?php

namespace App\Providers;

use App\Services\JobOffer\JobOfferService;
use App\Services\Response\ResponseService;
use Illuminate\Support\ServiceProvider;

class JobOfferServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(JobOfferService::class, function ($app) {
            return new JobOfferService(
                $app->make(ResponseService::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
