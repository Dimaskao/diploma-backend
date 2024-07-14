<?php

namespace Tests\Feature\TestsHelpers;

use App\Interfaces\ProfileStrategy;
use App\Models\User;
use App\Services\CompanyProfileService;
use App\Services\RegularUserProfileService;
use App\Strategies\CompanyProfileStrategy;
use App\Strategies\RegularUserProfileStrategy;

trait UserProfileHelper
{
    use AuthHelper;

    protected $profileService;
    protected ProfileStrategy $strategy;


    protected function setUpRegularUserProfileStrategy()
    {
        $this->profileService = new RegularUserProfileService();
        $this->strategy = new RegularUserProfileStrategy($this->profileService);
    }

    protected function setUpCompanyProfileStrategy()
    {
        $this->profileService = new CompanyProfileService();
        $this->strategy = new CompanyProfileStrategy($this->profileService);
    }

    private function authRegularUser()
    {
        $this->registerRegularUser();
        $this->login($this->getRegularUserRegistrationCredentials());
    }

    private function getRegularTestUser(): User
    {
        $this->authRegularUser();
        return User::where('email', $this->getRegularUserRegistrationCredentials()['email'])->first();
    }

    private function authCompany()
    {
        $this->registerCompany();
        $this->login($this->getCompanyRegistrationCredentials());
    }

    private function getCompanyTestUser(): User
    {
        $this->authCompany();
        return User::where('email', $this->getCompanyRegistrationCredentials()['email'])->first();
    }
}
