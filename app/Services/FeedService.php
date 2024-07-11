<?php

namespace App\Services;

class FeedService
{
    private ?string $currentUserId = null;

    public function setUser(string $userId): void
    {
        $this->currentUserId = $userId;
    }

    public function __construct(private readonly PostService $postService)
    {

    }

    public function getPosts(string $userId, int $page, int $limit): array
    {
        //TODO: add recommendations
        return $this->postService->getUserSubscriptionsPosts($userId, $page, $limit);
    }
}
