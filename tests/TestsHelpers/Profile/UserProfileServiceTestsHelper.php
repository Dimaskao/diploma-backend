<?php

namespace TestsHelpers\Profile;

use App\Factories\ProfileStrategyFactory;
use App\Interfaces\Factory;
use App\Interfaces\ProfileStrategy;
use App\Interfaces\SpecificProfileService;
use App\Models\User;
use App\Services\Profile\UserProfileService;
use App\Services\Response\ResponseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use TestsEnums\Entity;
use TestsEnums\Method;
use TestsEnums\Status;
use TestsHelpers\Auth\AuthHelper;
use TestsHelpers\Profile\Users\UsersHelper;

trait UserProfileServiceTestsHelper
{
    use RefreshDatabase, UsersHelper, AuthHelper;

    protected SpecificProfileService $service;
    protected ProfileStrategy $strategy;
    protected User $user;
    protected SpecificProfileService $profileRegistry;
    protected Factory $factory;
    protected UserProfileService $profileService;

    protected function setUpUserProfileService(ProfileStrategyFactory $factory, SpecificProfileService $service, $role): void
    {
        $this->factory = $factory;
        $this->role = $role;
        $this->service = $service;
        $this->setUpProfileRegistry($this->service, $this->role);
        $this->profileService = new UserProfileService($this->factory, new ResponseService());
    }

    protected function setUpProfileRegistry($registry, $role): void
    {
        $this->profileRegistry = $registry;
        $this->role = $role;
        $this->setUpAuth();
        $this->setUpRequest();
        $this->user = $this->getTestUser();
    }

    protected function setUpAuth(): void
    {
        $this->setUpRegistry(Entity::SERVICE, $this->role);
    }

    public function testGetProfileSuccess(): void
    {
        $response = $this->profileService->getProfile($this->user->id);
        $this->expectedResult(Method::GET, Status::SUCCESS, $response);
    }

    public function testUpdateProfileSuccess(): void
    {
        $response = $this->profileService->updateProfile($this->request, $this->user->id);
        $this->expectedResult(Method::UPDATE, Status::SUCCESS, $response);
    }

    public function testDeleteProfileSuccess(): void
    {
        $response = $this->profileService->deleteProfile($this->user->id);
        $this->expectedResult(Method::DELETE, Status::SUCCESS, $response);
    }
}
