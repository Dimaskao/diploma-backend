<?php

namespace App\Strategies\Profile;

use App\Services\Profile\SpecificProfile\Company\CompanyProfileService;

class CompanyProfileStrategy extends BaseProfileStrategy
{
    public function __construct(CompanyProfileService $service)
    {
        parent::__construct($service);
    }
}
