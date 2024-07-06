<?php

namespace App\Factories;

use App\Enums\UserRole;
use App\Interfaces\Factory;
use App\Interfaces\ProfileStrategy;
use App\Strategies\CompanyProfileStrategy;
use App\Strategies\RegularUserProfileStrategy;
use App\Models\User;
use Exception;

class ProfileStrategyFactory implements Factory
{
    /**
     * @throws Exception
     */
    public function create($params = []) : ProfileStrategy
    {
        if (isset($params['id'])) {
            $user = User::find($params['id']);

            if (!$user) {
                throw new Exception('User not found');
            }

            return match ($user->role->name) {
                UserRole::REGULAR_USER => new RegularUserProfileStrategy(),
                UserRole::COMPANY => new CompanyProfileStrategy(),
                default => throw new Exception('Invalid user role'),
            };
        }
        throw new Exception('Id not found');
    }
}
