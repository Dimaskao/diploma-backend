<?php

namespace App\Providers;

use App\Factories\ProfileStrategyFactory;
use App\Services\Image\ImageUploadService;
use App\Services\Response\ResponseService;
use App\Services\Validation\ValidationService;
use Illuminate\Support\ServiceProvider;

class ProfileStrategyFactoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ProfileStrategyFactory::class, function ($app) {
            return new ProfileStrategyFactory(
                $app->make(ValidationService::class),
                $app->make(ResponseService::class),
                $app->make(ImageUploadService::class)
            );
        });
    }
}
