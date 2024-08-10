<?php

namespace App\Services\Image;

use Illuminate\Support\Facades\Log;

readonly class ImageUploadService
{
    protected ImageProcessingService $imageProcessingService;

    public function __construct(ImageProcessingService $imageProcessingService)
    {
        $this->imageProcessingService = $imageProcessingService;
    }

    /**
     * Upload a file to AWS S3 and return the URL.
     */
    public function uploadToCloud($model, $file, string $collectionName, bool $isMultiple = false): ?string
    {
        $media = $this->imageProcessingService->saveToCloud($model, $file, $collectionName, $isMultiple);
        return $this->imageProcessingService->getImageUrl($media);
    }

    /**
     * Update uploaded to AWS S3 file and return the URL.
     */
    public function updateUploadedFile($oldFileUrl, $model, $newFile, $collectionName, $isMultiple = false): ?string
    {
        $oldMedia = $this->imageProcessingService->getFileByMediaUrl($oldFileUrl);
        $this->imageProcessingService->deleteImageFromAws($oldMedia);
        return $this->uploadToCloud($model, $newFile, $collectionName, $isMultiple);
    }
}
