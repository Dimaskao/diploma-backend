<?php

namespace App\Services\Image;

use App\Models\User;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class ImageUploadService
{
    protected ImageProcessingService $imageProcessingService;

    public function __construct(ImageProcessingService $imageProcessingService)
    {
        $this->imageProcessingService = $imageProcessingService;
    }

    /**
     * Uploads image to AWS and returns url to saved media file
     */
    public function upload(User $user, ?UploadedFile $file): ?string
    {
        // TODO: setup aws in .env
        try {
            $media = $this->imageProcessingService->saveImageToAWS($user, $file, 'avatars');
            return $this->imageProcessingService->getImageUrl($media);
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Removes media file from AWS
     */
    public function remove(User $user): bool
    {
        $media = $this->imageProcessingService->getFileByMediaUrl($user->avatar_url);
        return $this->imageProcessingService->deleteImageFromAws($media);
    }
}
