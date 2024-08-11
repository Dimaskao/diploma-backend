<?php

namespace Services\Image;

use App\Enums\UserRole;
use App\Services\Image\ImageProcessingService;
use App\Services\Image\ImageUploadService;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;
use TestsHelpers\Image\ImageUploadServiceTestsHelper;

class ImageUploadServiceTest extends TestCase
{
    use ImageUploadServiceTestsHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->setUpImageService(new ImageUploadService(new ImageProcessingService()), UserRole::REGULAR_USER);
    }
}
