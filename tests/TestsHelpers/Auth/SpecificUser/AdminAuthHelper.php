<?php

namespace TestsHelpers\Auth\SpecificUser;

trait AdminAuthHelper
{
    public function getAdminRegistrationCredentials(): array
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
