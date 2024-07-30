<?php

namespace App\Factories;

use App\Enums\UserRole;
use App\Interfaces\Factory;
use App\Interfaces\ProfileStrategy;
use App\Models\User;
use App\Services\Profile\AdminProfileService;
use App\Services\Profile\CompanyProfileService;
use App\Services\Profile\RegularUserProfileService;
use App\Strategies\Profile\AdminProfileStrategy;
use App\Strategies\Profile\CompanyProfileStrategy;
use App\Strategies\Profile\RegularUserProfileStrategy;
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
                UserRole::REGULAR_USER => new RegularUserProfileStrategy(new RegularUserProfileService()),
                UserRole::COMPANY => new CompanyProfileStrategy(new CompanyProfileService()),
                UserRole::ADMIN => new AdminProfileStrategy(new AdminProfileService()),
                default => throw new Exception('Invalid user role'),
            };
        }
        throw new Exception('Id not found');
    }
}
