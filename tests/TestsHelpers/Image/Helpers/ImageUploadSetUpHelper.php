<?php

namespace TestsHelpers\Image\Helpers;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use TestsEnums\Entity;

trait ImageUploadSetUpHelper
{
    /** ImageProcessingService or ImageUploadService */
    protected mixed $imageService;
    protected User $user;
    protected string $role;
    protected string $baseTestImagesFolderPath;

    protected function setUpImageService($imageService, $role): void
    {
        $this->role = $role;
        $this->imageService = $imageService;
        $this->setUpRegistry(Entity::SERVICE, $this->role);
        $this->user = $this->userTest();
        $this->baseTestImagesFolderPath = 'tests/Fixtures/';
        $this->createTestAvatar();

        // Set up the storage to use a temporary directory
        Storage::fake('s3');
    }

    protected function createTestAvatar(): void
    {
        copy(base_path($this->baseTestImagesFolderPath .'avatar_example.jpg'), $this->baseTestImagesFolderPath .'/avatar.jpg');
    }

    protected function uploadedFile(): UploadedFile
    {
        return new UploadedFile(
            base_path('tests/Fixtures/avatar.jpg'),
            'avatar.jpg',
            'image/jpeg',
            null,
            true
        );
    }


    protected function saveToAws(): ?Media
    {
        return $this->imageService->saveToCloud($this->user, $this->uploadedFile(), 'avatars.urls');
    }

    protected function upload($isFake = false): ?string
    {
        return $this->imageService->uploadToCloud($this->user, !$isFake ? $this->uploadedFile() : null);
    }
}
