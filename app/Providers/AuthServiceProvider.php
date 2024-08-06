<?php

namespace App\Providers;

use App\Factories\UserFactory;
use App\Services\Auth\AuthService;
use App\Services\Image\ImageProcessingService;
use App\Services\Response\ResponseService;
use App\Services\Validation\ValidationService;
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
                $app->make(ImageProcessingService::class)
            );
        });
    }
}
