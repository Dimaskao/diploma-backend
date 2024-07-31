<?php

namespace App\Services\Profile\SpecificProfile\Company\Handlers\Get\Helpers;

use App\Models\Post;
use App\Models\PostImage;

trait PostsGetHelper
{
    protected function getUserPosts($user): array
    {
        return Post::where('user_id', $user->id)->get()->map(function ($post) {
            return [
                'title' => $post->title,
                'content' => $post->content,
                'images' => $this->getPostImages($post),
            ];
        })->toArray();
    }

    private function getPostImages($post): array
    {
        return PostImage::where('post_id', $post->id)->get()->map(function ($postImage) {
            return ['url' => $postImage->url];
        })->toArray();
    }
}
