<?php

namespace App\Strategies\Profile\SpecificProfile;

use App\Services\Profile\SpecificProfile\RegularUser\RegularUserProfileService;
use App\Strategies\Profile\BaseProfileStrategy;

class RegularUserProfileStrategy extends BaseProfileStrategy
{
    public function __construct(RegularUserProfileService $service)
    {
        parent::__construct($service);
    }
}
