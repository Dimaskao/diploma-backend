<?php

namespace TestsHelpers\Auth\SpecificUser;

trait RegularUserAuthHelper
{
    public function getRegularUserRegistrationCredentials(): array
    {
        return [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => EmailAuthHelper::generateRandomEmail(),
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ];
    }
}
