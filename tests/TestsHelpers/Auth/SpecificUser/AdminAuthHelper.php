<?php

namespace TestsHelpers\Auth\SpecificUser;

trait AdminAuthHelper
{
    public function getAdminRegistrationCredentials(): array
    {
        return [
            'name' => 'Test Company',
            'email' => EmailAuthHelper::generateRandomEmail(),
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'company',
        ];
    }
}
