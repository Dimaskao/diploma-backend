<?php

namespace TestsHelpers\Auth\SpecificUser;

trait CompanyAuthHelper
{
    public function getCompanyRegistrationCredentials(): array
    {
        return [
            'name' => 'Test Company',
            'email' => 'test.company@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'company',
        ];
    }
}
