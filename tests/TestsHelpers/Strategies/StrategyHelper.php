<?php

namespace TestsHelpers\Strategies;

use App\Interfaces\ProfileStrategy;
use App\Interfaces\SpecificProfileService;
use App\Models\User;
use App\Strategies\Profile\BaseProfileStrategy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TestsEnums\Entity;
use TestsHelpers\Auth\AuthHelper;
use TestsHelpers\Profile\Users\UsersHelper;

trait StrategyHelper
{
    use RefreshDatabase, UsersHelper, AuthHelper;

    protected SpecificProfileService $service;
    protected ProfileStrategy $strategy;
    protected User $user;
    protected SpecificProfileService $profileRegistry;

    protected function setUpProfileStrategy(BaseProfileStrategy $strategy, $role): void
    {
        $this->strategy = $strategy;
        $this->role = $role;
        $this->setUpProfileRegistry($this->service, $this->role);
    }

    protected function setUpProfileRegistry($registry, $role): void
    {
        $this->profileRegistry = $registry;
        $this->role = $role;
        $this->setUpAuth();
        $this->setUpRequest();
        $this->user = $this->userTest();
    }

    protected function setUpAuth(): void
    {
        $this->setUpRegistry(Entity::SERVICE, $this->role);
    }
}
