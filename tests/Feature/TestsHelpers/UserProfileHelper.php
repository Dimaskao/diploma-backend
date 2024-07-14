<?php

namespace Tests\Feature\TestsHelpers;

use App\Models\User;

trait UserProfileHelper
{
    use AuthHelper;

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
