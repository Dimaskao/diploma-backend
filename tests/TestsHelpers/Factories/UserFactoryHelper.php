<?php

namespace TestsHelpers\Factories;

use App\Enums\UserRole;
use App\Services\Image\ImageProcessingService;
use App\Services\Image\ImageUploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TestsHelpers\Auth\AuthHelper;
use TestsHelpers\Image\Helpers\ImageUploadSetUpHelper;
use TestsHelpers\Profile\Users\UsersHelper;

trait UserFactoryHelper
{
    use RefreshDatabase, ImageUploadSetUpHelper, AuthHelper, UsersHelper;

    protected function setUpUserFactory(): void
    {
        $this->setUpImageService(new ImageUploadService(
            new ImageProcessingService()),UserRole::REGULAR_USER);
    }
}
