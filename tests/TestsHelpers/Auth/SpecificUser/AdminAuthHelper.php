<?php

namespace TestsHelpers\Auth\SpecificUser;

trait AdminAuthHelper
{
    public function getAdminRegistrationCredentials(): array
    {
        return [
            'name' => 'Test Admin test',
            'email' => EmailAuthHelper::generateRandomEmail(),
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
            'permissions' => [
                'read' => true,
                'edit' => true,
                'write' => true,
                'full' => true
            ]
        ];
    }
}
