<?php

namespace TestsHelpers\Image;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use TestsHelpers\Auth\AuthHelper;
use TestsHelpers\Image\Helpers\ImageUploadSetUpHelper;
use TestsHelpers\Profile\Users\UsersHelper;

trait ImageProcessingServiceTestsHelper
{
    use RefreshDatabase, AuthHelper, UsersHelper, ImageUploadSetUpHelper;

    public function testSaveImageToAws(): void
    {
        $media = $this->saveToAws();
        $this->assertInstanceOf(Media::class, $media);

        $files = Storage::disk('s3')->allFiles();
        $expectedPath = "{$media->id}/{$media->file_name}";

        $this->assertTrue(in_array($expectedPath, $files));
        Storage::disk('s3')->assertExists($expectedPath);
    }

    public function testDeleteImageFromAws(): void
    {
        $media = $this->saveToAws();
        $result = $this->imageService->deleteImageFromAws($media);
        $this->assertTrue($result);
        Storage::disk('s3')->assertMissing("{$media->id}/{$media->file_name}");
    }

    public function testGetImageUrl(): void
    {
        $media = $this->saveToAws();
        $url = $this->imageService->getImageUrl($media);

        $this->assertNotEmpty($url);
        $this->assertStringContainsString($media->id, $url);

        $media->delete();
    }
}
