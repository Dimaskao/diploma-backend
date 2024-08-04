<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;

class FeedService
{

    public function __construct(private readonly PostService $postService) {}

    public function getPosts(string $userId, int $page, int $limit): LengthAwarePaginator
    {
        //TODO: add recommendations
        return $this->postService->getUserSubscriptionsPosts($userId, $page, $limit);
    }
}
