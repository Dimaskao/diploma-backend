<?php

namespace Tests\TestsHelpers\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use TestsEnums\Entity;
use TestsEnums\Method;
use TestsEnums\Status;
use TestsHelpers\Auth\AuthHelper;
use TestsHelpers\Profile\Users\UsersHelper;

trait ProfileServiceTestsHelper
{
    use RefreshDatabase, AuthHelper, UsersHelper;

    /** SpecificProfileService entity */
    protected mixed $profileRegistry = null;
    protected Request $request;
    protected User $user;

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

    public function testGetProfileSuccess(): void
    {
        $this->refreshCredentials();
        $response = $this->profileRegistry->getProfile($this->user);
        $this->expectedResult(Method::GET, Status::SUCCESS, $response);
    }

    public function testUpdateProfileSuccess(): void
    {
        $response = $this->profileRegistry->updateProfile($this->userTest(), $this->request);
        $this->expectedResult(Method::UPDATE, Status::SUCCESS, $response);
    }

    public function testUpdateProfileFailed(): void
    {
        $response = $this->profileRegistry->updateProfile([], new Request([]));
        $this->expectedResult(Method::UPDATE, Status::FAILED, $response);
    }

    public function testDeleteProfileSuccess(): void
    {
        $response = $this->profileRegistry->deleteProfile($this->userTest()->id);
        $this->expectedResult(Method::DELETE, Status::SUCCESS, $response);
    }

    public function testDeleteProfileFailed(): void
    {
        $response = $this->profileRegistry->deleteProfile('test_id');
        $this->expectedResult(Method::DELETE, Status::FAILED, $response);
    }
}
