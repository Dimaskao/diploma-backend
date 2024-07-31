<?php

namespace TestsHelpers\Controllers\SocialNetwork;

use App\Http\Controllers\SocialNetworkController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TestsHelpers\SocialNetwork\SocialNetwork\SocialNetworkServiceTestsHelper;

trait SocialNetworkControllerHelper
{
    use RefreshDatabase, SocialNetworkServiceTestsHelper;

    protected SocialNetworkController $controller;

    protected function setUpSocialNetworkController($role): void
    {
        $this->setUpSocialNetworkService($role);
        $this->controller = new SocialNetworkController($this->socialNetworkService);
    }
}
