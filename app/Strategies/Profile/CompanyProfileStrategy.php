<?php

namespace App\Strategies\Profile;

use App\Services\Profile\CompanyProfileService;

class CompanyProfileStrategy extends BaseProfileStrategy
{
    public function __construct(CompanyProfileService $service)
    {
        parent::__construct($service);
    }
}
