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

    private function getRegularTestUser() : User
    {
        $this->authRegularUser();
        return User::where('email', $this->getRegularUserRegistrationCredentials()['email'])->first();
    }

//    private function getRegularTestUserParams() : array
//    {
//        return [
//            'first_name' =>  'regular_user_first_name',
//            'last_name' => 'regular_user_last_name',
//            'password' => '12345678',
//            'email' => 'regular_user_test@test.com',
//            'role' => 'user'
//        ];
//    }
}
