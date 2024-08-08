<?php

namespace App\Services\Image;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageProcessingService
{
    /**
     * @throws Exception
     */
    public function saveImageToAWS($model, ?UploadedFile $file, string $collectionName): ?Media
    {
        $this->validateModel($model, $file);
        return $model->addMedia($file)->toMediaCollection($collectionName, 's3');
    }

    public function deleteImageFromAws(Media $media): bool
    {
        return $media->delete();
    }

    public function getImageUrl(?Media $media): string
    {
        return $media->getUrl();
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
        if (preg_match('/\/(\d+)\//', $url, $matches)) {
            return (int) $matches[1];
        }
        return null;
    }
}
