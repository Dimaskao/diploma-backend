<?php

namespace App\Services\Image;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ImageUploadService
{
    protected ImageProcessingService $imageProcessingService;

    public function __construct()
    {
        $this->imageProcessingService = new ImageProcessingService();
    }

    /**
     * Uploads image to AWS and returns url to saved media file
     */
    public function upload(User $user, UploadedFile $file): ?string
    {
        try {
            $media = $this->imageProcessingService->saveImageToAWS($user, $file, 'avatars');
            return $this->imageProcessingService->getImageUrl($media);
        } catch (Exception $e) {
            return null;
        }
    }
}
