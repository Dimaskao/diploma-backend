<?php

namespace App\Providers;

use App\Factories\UserFactory;
use App\Services\Auth\AuthService;
use App\Services\Response\ResponseService;
use App\Services\ValidationService;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AuthService::class, function ($app) {
            return new AuthService(
                $app->make(ValidationService::class),
                $app->make(UserFactory::class),
                $app->make(ResponseService::class),
            );
        });
    }
}
