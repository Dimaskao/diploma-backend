<?php

namespace App\Services\Image;

use Exception;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageProcessingService
{
    /**
     * @throws Exception
     */
    public function saveImageToAWS($model, UploadedFile $file, string $collectionName): ?Media
    {
        $this->validateModel($model);
        return $model->addMedia($file)->toMediaCollection($collectionName, 's3');
    }

    public function deleteImageFromAws(Media $media): bool
    {
        return $media->delete();
    }

    public function getImageUrl(Media $media): string
    {
        return $media->getUrl();
    }

    /**
     * @throws Exception
     */
    private function validateModel($model): void
    {
        if (!method_exists($model, 'addMedia')) {
            throw new Exception("Model does not use the HasMedia trait");
        }
    }
}
