<?php

namespace App\Policies;

use App\Enums\Period;
use App\Enums\PostVisibility;
use App\Enums\UserRole;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

class CommentPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Comment $comment): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->role->name == UserRole::ADMIN;
    }
}
