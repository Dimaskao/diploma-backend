<?php

namespace App\Providers;

use App\Services\Image\ImageUploadService;
use App\Services\Profile\SpecificProfile\Admin\AdminProfileService;
use App\Services\Response\ResponseService;
use App\Services\Validation\ValidationService;
use Illuminate\Support\ServiceProvider;

class AdminProfileServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AdminProfileService::class, function ($app) {
            return new AdminProfileService(
                $app->make(ValidationService::class),
                $app->make(ResponseService::class),
                $app->make(ImageUploadService::class)
            );
        });
    }
}
