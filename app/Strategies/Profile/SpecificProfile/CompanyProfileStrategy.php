<?php

namespace App\Strategies\Profile\SpecificProfile;

use App\Services\Profile\SpecificProfile\Company\CompanyProfileService;
use App\Strategies\Profile\BaseProfileStrategy;

class CompanyProfileStrategy extends BaseProfileStrategy
{
    public function __construct(CompanyProfileService $service)
    {
        parent::__construct($service);
    }
}
