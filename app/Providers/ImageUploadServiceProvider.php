<?php

namespace App\Providers;

use App\Factories\UserFactory;
use App\Services\Auth\AuthService;
use App\Services\Image\ImageProcessingService;
use App\Services\Image\ImageUploadService;
use App\Services\Response\ResponseService;
use App\Services\Validation\ValidationService;
use Illuminate\Support\ServiceProvider;

class ImageUploadServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ImageUploadService::class, function ($app) {
            return new ImageUploadService(
                $app->make(ImageProcessingService::class)
            );
        });
    }
}
