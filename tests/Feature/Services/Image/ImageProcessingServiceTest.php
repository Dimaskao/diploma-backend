<?php

namespace Services\Image;

use App\Enums\UserRole;
use App\Services\Image\ImageProcessingService;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;
use TestsHelpers\Image\ImageProcessingServiceTestsHelper;

class ImageProcessingServiceTest extends TestCase
{
    use ImageProcessingServiceTestsHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->setUpImageService(new ImageProcessingService(), UserRole::REGULAR_USER);
    }
}
