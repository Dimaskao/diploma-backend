<?php

namespace App\Services\Profile\SpecificProfile\Company\Handlers\Get;

use App\Enums\ResponseKey;
use App\Services\Profile\SpecificProfile\Company\Handlers\Get\Helpers\JobOffersGetHelper;
use App\Services\Profile\SpecificProfile\Company\Handlers\Get\Helpers\PostsGetHelper;
use App\Services\Profile\SpecificProfile\Company\Handlers\Get\Helpers\ProfileGetHelper;
use Illuminate\Support\Facades\Log;

trait GetHandler
{
    use ProfileGetHelper, PostsGetHelper, JobOffersGetHelper;

    protected function getProfileMethods(): array
    {
        return [
            ResponseKey::COMPANY => 'getCompanyProfileData',
            ResponseKey::POSTS => 'getUserPosts',
            ResponseKey::JOB_OFFERS => 'getCompanyJobOffers'
        ];
    }
}
