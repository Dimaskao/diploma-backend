<?php

namespace App\Providers;

use App\Services\Image\ImageUploadService;
use App\Services\Profile\SpecificProfile\RegularUser\RegularUserProfileService;
use App\Services\Response\ResponseService;
use App\Services\Validation\ValidationService;
use Illuminate\Support\ServiceProvider;

class RegularUserProfileServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(RegularUserProfileService::class, function ($app) {
            return new RegularUserProfileService(
                $app->make(ValidationService::class),
                $app->make(ResponseService::class),
                $app->make(ImageUploadService::class)
            );
        });
    }
}
