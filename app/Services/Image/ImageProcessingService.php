<?php

namespace App\Services\Image;

use Exception;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ImageProcessingService
{
    /**
     * @throws Exception
     */
    public function saveImageToAws($model, string $imagePath, string $collectionName): ?Media
    {
        if (!method_exists($model, 'addMedia')) {
            throw new Exception("Model does not use the HasMedia trait");
        }

        // Add media to the specified collection and save to AWS
        return $model->addMedia($imagePath)
            ->toMediaCollection($collectionName, 's3');
    }

    public function deleteImageFromAws(Media $media): bool
    {
        return $media->delete();
    }

    public function getImageUrl(Media $media): string
    {
        return $media->getUrl();
    }
}
