<?php

namespace App\Strategies\Profile;

use App\Services\Profile\SpecificProfile\RegularUser\RegularUserProfileService;

class RegularUserProfileStrategy extends BaseProfileStrategy
{
    public function __construct(RegularUserProfileService $service)
    {
        parent::__construct($service);
    }
}
