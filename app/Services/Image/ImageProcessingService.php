<?php

namespace App\Services\Image;

use Exception;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

readonly class ImageProcessingService
{
    public function saveToCloud($model, $requestData, string $collectionName, bool $isMultiple = false): ?Media
    {
        try {
            $this->validateModel($model, $requestData);
            return $this->addMedia($model, $requestData, $isMultiple)->toMediaCollection($collectionName, 's3');
        } catch (Exception $e) {
            Log::error('Error uploading image to AWS S3: ' . $e->getMessage());
            return null;
        }
    }

    private function addMedia($model, $requestData, bool $isMultiple = false)
    {
        return $isMultiple ? $model->addMultipleMediaFromRequest($requestData) : $model->addMedia($requestData);
    }

    public function deleteImageFromAws(Media $media): bool
    {
        return $media->delete();
    }

    public function getImageUrl(?Media $media): string
    {
        return $media->exists ? $media->getUrl() : '';
    }

    /**
     * @throws Exception
     */
    private function validateModel($model, $file): void
    {
        if (!method_exists($model, 'addMedia')) {
            throw new Exception("Model does not use the HasMedia trait");
        }

        if ($file === null) {
            throw new Exception("No file provided for upload.");
        }
    }


    /**
     * Get the file by media URL.
     */
    public function getFileByMediaUrl(string $mediaUrl): ?Media
    {
        $mediaId = $this->extractMediaIdFromUrl($mediaUrl);

        if (!$mediaId) {
            return null;
        }

        $media = Media::find($mediaId);
        return $media ?: null;
    }

    /**
     * Extract the media ID from the media URL.
     */
    public function extractMediaIdFromUrl(string $url): ?int
    {
        return preg_match('/\/(\d+)\//', $url, $matches) ? (int)$matches[1]: null;
    }
}
