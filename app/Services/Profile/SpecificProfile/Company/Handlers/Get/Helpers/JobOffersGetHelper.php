<?php

namespace App\Services\Profile\SpecificProfile\Company\Handlers\Get\Helpers;

use App\Models\JobOffer;
use App\Models\JobOfferSkill;

trait JobOffersGetHelper
{
    protected function getCompanyJobOffers($company): array
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
