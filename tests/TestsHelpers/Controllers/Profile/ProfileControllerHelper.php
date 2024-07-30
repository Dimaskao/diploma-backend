<?php

namespace TestsHelpers\Controllers\Profile;

use App\Factories\ProfileStrategyFactory;
use App\Http\Controllers\ProfileController;
use App\Interfaces\SpecificProfileService;
use App\Strategies\Profile\BaseProfileStrategy;
use TestsEnums\Method;
use TestsEnums\Status;
use TestsHelpers\Profile\UserProfileServiceTestsHelper;

trait ProfileControllerHelper
{
    use UserProfileServiceTestsHelper;

    protected ProfileController $controller;

    protected function setUpProfileController(BaseProfileStrategy $strategy, SpecificProfileService $service, $role): void
    {
        $this->strategy = $strategy;
        $this->setUpUserProfileService(new ProfileStrategyFactory(), $service, $role);
        $this->controller = new ProfileController($this->profileService);
    }
}
