<?php

namespace App\Services\Profile\SpecificProfile\Company\Handlers\Delete;

use App\Models\JobOffer;
use App\Models\JobOfferSkill;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;

trait DeleteHandler
{
    protected function delete(User $base): void
    {
        $company = $base->userProfile->company;

        $jobOffers = JobOffer::where('company_id', $company->id)->get();
        foreach ($jobOffers as $jobOffer) {
            JobOfferSkill::where('job_offer_id', $jobOffer->id)->delete();
            $jobOffer->delete();
        }

        $posts = Post::where('user_id', $base->id)->get();
        foreach ($posts as $post) {
            PostImage::where('post_id', $post->id)->delete();
            $post->delete();
        }

        $company->delete();
        $base->userProfile->delete();
        $base->delete();
    }
}
