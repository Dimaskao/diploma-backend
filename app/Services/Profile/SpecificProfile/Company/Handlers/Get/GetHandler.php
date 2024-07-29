<?php

namespace App\Services\Profile\SpecificProfile\Company\Handlers\Get;

use App\Enums\ResponseKey;
use App\Models\JobOffer;
use App\Models\JobOfferSkill;
use App\Models\Post;
use App\Models\PostImage;

trait GetHandler
{
    protected function getResponseProfileData($user, $company): array
    {
        return [
            ResponseKey::PROFILE => [
                ResponseKey::COMPANY => [
                    'id' => $user->id,
                    'name' => $company->name,
                    'description' => $company->description,
                    'contact_email' => $company->contact_email,
                    'contact_phone' => $company->contact_phone,
                    'contact_url' => $company->contact_url,
                ],
                ResponseKey::POSTS => $this->getUserPosts($user),
                ResponseKey::JOB_OFFERS => $this->getCompanyJobOffers($company)
            ]
        ];
    }

    private function getUserPosts($user): array
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

    private function getCompanyJobOffers($company): array
    {
        return JobOffer::where('company_id', $company->id)->get()->map(function ($jobOffer) {
            return [
                'id' => $jobOffer->id,
                'title' => $jobOffer->title,
                'position' => $jobOffer->position,
                'description' => $jobOffer->description,
                'requirements' => $jobOffer->requirements,
                'requirement_experience' => $jobOffer->requirement_experience,
                'date_posted' => $jobOffer->date_posted,
                'valid_until' => $jobOffer->valid_until,
                'skills' => $this->getJobOfferSkills($jobOffer)
            ];
        })->toArray();
    }

    private function getJobOfferSkills($jobOffer): array
    {
        return JobOfferSkill::where('job_offer_id', $jobOffer->id)->get()->map(function ($jobOfferSkill) {
            return [
                'id' => $jobOfferSkill->id,
                'name' => $jobOfferSkill->name
            ];
        })->toArray();
    }
}
