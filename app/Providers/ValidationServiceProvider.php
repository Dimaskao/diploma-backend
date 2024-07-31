<?php

namespace App\Providers;

use App\Services\Validation\ValidationService;
use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ValidationService::class, function ($app) {
            return new ValidationService();
        });
    }
}
