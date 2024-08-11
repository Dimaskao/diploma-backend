<?php

namespace App\Providers;

use App\Services\Image\ImageUploadService;
use App\Services\Profile\SpecificProfile\Company\CompanyProfileService;
use App\Services\Response\ResponseService;
use App\Services\Validation\ValidationService;
use Illuminate\Support\ServiceProvider;

class CompanyProfileServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CompanyProfileService::class, function ($app) {
            return new CompanyProfileService(
                $app->make(ValidationService::class),
                $app->make(ResponseService::class),
                $app->make(ImageUploadService::class)
            );
        });
    }
}
