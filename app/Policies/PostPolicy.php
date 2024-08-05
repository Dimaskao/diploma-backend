<?php

namespace App\Policies;

use App\Enums\Period;
use App\Enums\PostVisibility;
use App\Enums\UserRole;
use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        if ($post->visibility == PostVisibility::Public->value || $user->id === $post->user_id) {
            return true;
        }

        return $user->subscriptions()->where('subscription_id', $post->user_id)->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id || $user->role->name == UserRole::ADMIN;
    }
}
