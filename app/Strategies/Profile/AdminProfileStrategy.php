<?php

namespace App\Strategies\Profile;

use App\Services\Profile\AdminProfileService;

class AdminProfileStrategy extends BaseProfileStrategy
{
    public function __construct(AdminProfileService $service)
    {
        parent::__construct($service);
    }
}
