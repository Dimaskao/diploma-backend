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

    private function getCompanyProfile(mixed $user): array
    {
        $company = Company::where('id', $user->company_id)->first();

        return [
            'id' => $user->id,
            'name' => $company->name,
            'description' => $company->description,
            'contactEmail' => $company->contact_email,
            'contactPhone' => $company->contact_phone,
            'contactUrl' => $company->contact_url,
            'posts' => $this->getUserPosts($user),
            'jobOffers' => $this->getCompanyJobOffers($company)
        ];
    }

    public function getCompanyJobOffers($company): array
    {
        $jobOffers = [];
        foreach ((JobOffer::where('company_id', $company->id)->get()) as $jobOffer) {
            $jobOffers[] = [
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
        }
        return $jobOffers;
    }

    public function getJobOfferSkills($jobOffer): array
    {
        $skills = [];
        foreach ((JobOfferSkill::where('job_offer_id', $jobOffer->id)->get()) as $jobOfferSkill) {
            $skills[] = [
                'id' => $jobOfferSkill->id,
                'name' => $jobOfferSkill->name
            ];
        }
        return $skills;
    }

    public function getUserPosts($user): array
    {
        $posts = [];
        foreach ((Post::where('user_id', $user->id)->get()) as $companyPost) {
            $posts[] = [
                'title' => $companyPost->title,
                'content' => $companyPost->content,
                'images' => $this->getPostImages($companyPost),
            ];
        }
        return $posts;
    }

    public function getPostImages($post): array
    {
        $images = [];
        foreach ((PostImage::where('post_id', $post->id)->get()) as $postImage) {
            $images[] = ['url' => $postImage->url];
        }
        return $images;
    }
}
