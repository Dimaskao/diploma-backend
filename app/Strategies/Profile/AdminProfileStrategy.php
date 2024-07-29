<?php

namespace App\Strategies\Profile;

use App\Services\Profile\SpecificProfile\Admin\AdminProfileService;

class AdminProfileStrategy extends BaseProfileStrategy
{
    public function __construct(AdminProfileService $service)
    {
        parent::__construct($service);
    }
}
