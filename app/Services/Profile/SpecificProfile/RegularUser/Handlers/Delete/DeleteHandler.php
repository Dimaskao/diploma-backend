<?php

namespace App\Services\Profile\SpecificProfile\RegularUser\Handlers\Delete;

use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use App\Models\UserContact;
use App\Models\UserEducation;
use App\Models\UserSkill;
use App\Models\WorkExperience;

trait DeleteHandler
{
    protected function delete(User $base): void
    {
        $regularUser = $base->userProfile->regularUser;

        UserEducation::where('user_id', $regularUser->id)->delete();
        UserSkill::where('user_id', $regularUser->id)->delete();
        WorkExperience::where('user_id', $regularUser->id)->delete();
        UserContact::where('subscriber_id', $regularUser->id)->delete();

        $posts = Post::where('user_id', $base->id)->get();
        foreach ($posts as $post) {
            PostImage::where('post_id', $post->id)->delete();
            $post->delete();
        }

        $regularUser->delete();
        $base->userProfile->delete();
        $base->delete();
    }
}
