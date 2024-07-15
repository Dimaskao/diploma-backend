<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\ValidationService;
use App\Factories\UserFactory;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AuthService::class, function ($app) {
            return new AuthService(
                $app->make(ValidationService::class),
                $app->make(UserFactory::class)
            );
        });
    }
}
