<?php

namespace App\Strategies\Profile\SpecificProfile;

use App\Services\Profile\SpecificProfile\Admin\AdminProfileService;
use App\Strategies\Profile\BaseProfileStrategy;

class AdminProfileStrategy extends BaseProfileStrategy
{
    public function __construct(AdminProfileService $service)
    {
        parent::__construct($service);
    }
}
