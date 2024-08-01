<?php

namespace App\Services;

use App\Enums\PostStatus;
use App\Enums\PostVisibility;
use App\Models\Post;
use Illuminate\Pagination\LengthAwarePaginator;

class PostService
{
    private ?string $currentUserId = null;

    public function setUser(string $userId): void
    {
        $this->currentUserId = $userId;
    }

    public function getUserSubscriptionsPosts(string $userId, int $page, int $limit): LengthAwarePaginator
    {
        return Post::select('posts.*')
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->join('user_contacts', 'user_contacts.subscription_id', '=', 'users.id')
            ->where('user_contacts.subscriber_id', $userId)
            ->where('posts.status', PostStatus::Published->value)
            ->paginate($limit, '*', 'page', $page);
    }

    public function getUserPosts(string $userId, int $page, int $limit, bool $onlyPublicPosts): LengthAwarePaginator
    {
        $query = Post::where('status', PostStatus::Published->value)
            ->where('user_id', $userId);

        if ($onlyPublicPosts) {
            $query->where('visibility', PostVisibility::Public->value);
        }

        return $query->paginate($limit, '*', 'page', $page);
    }
}
