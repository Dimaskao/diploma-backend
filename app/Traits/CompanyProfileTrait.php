<?php

namespace App\Traits;

use App\Models\Company;
use App\Models\JobOffer;
use App\Models\JobOfferSkill;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait CompanyProfileTrait
{
    private function updateCompanyInformation($user, Request $request): JsonResponse
    {
        // Implement company update logic here

        return response()->json(['message' => 'Profile updated successfully', 'company' => $user]);
    }

    private function getCompanyProfile(mixed $user): JsonResponse
    {
        $company = $user->company;

        return response()->json([
            'profile' => [
                'id' => $user->id,
                'name' => $company->name,
                'description' => $company->description,
                'contactEmail' => $company->contact_email,
                'contactPhone' => $company->contact_phone,
                'contactUrl' => $company->contact_url,
                'posts' => $this->getUserPosts($user),
                'jobOffers' => $this->getCompanyJobOffers($company)
            ]
        ], 200);
    }

    public function getCompanyJobOffers($company): array
    {
        return JobOffer::where('company_id', $company->id)->get()->map(function ($jobOffer) {
            return [
                'id' => $jobOffer->id,
                'title' => $jobOffer->title,
                'position' => $jobOffer->position,
                'description' => $jobOffer->description,
                'requirements' => $jobOffer->requirements,
                'requirementExperience' => $jobOffer->requirement_experience,
                'datePosted' => $jobOffer->date_posted,
                'validUntil' => $jobOffer->valid_until,
                'skills' => $this->getJobOfferSkills($jobOffer)
            ];
        })->toArray();
    }

    public function getJobOfferSkills($jobOffer): array
    {
        return JobOfferSkill::where('job_offer_id', $jobOffer->id)->get()->map(function ($jobOfferSkill) {
            return [
                'id' => $jobOfferSkill->id,
                'name' => $jobOfferSkill->name
            ];
        })->toArray();
    }

    public function getUserPosts($user): array
    {
        return Post::where('user_id', $user->id)->get()->map(function ($post) {
            return [
                'title' => $post->title,
                'content' => $post->content,
                'images' => $this->getPostImages($post),
            ];
        })->toArray();
    }

    public function getPostImages($post): array
    {
        return PostImage::where('post_id', $post->id)->get()->map(function ($postImage) {
            return ['url' => $postImage->url];
        })->toArray();
    }
}
