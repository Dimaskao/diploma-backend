<?php

namespace App\Factories;

use App\Enums\UserRole;
use App\Interfaces\Factory;
use App\Interfaces\ProfileStrategy;
use App\Models\User;
use App\Services\Profile\SpecificProfile\Admin\AdminProfileService;
use App\Services\Profile\SpecificProfile\Company\CompanyProfileService;
use App\Services\Profile\SpecificProfile\RegularUser\RegularUserProfileService;
use App\Strategies\Profile\SpecificProfile\AdminProfileStrategy;
use App\Strategies\Profile\SpecificProfile\CompanyProfileStrategy;
use App\Strategies\Profile\SpecificProfile\RegularUserProfileStrategy;
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
                UserRole::REGULAR_USER => new RegularUserProfileStrategy(resolve(RegularUserProfileService::class)),
                UserRole::COMPANY => new CompanyProfileStrategy(resolve(CompanyProfileService::class)),
                UserRole::ADMIN => new AdminProfileStrategy(resolve(AdminProfileService::class)),
                default => throw new Exception('Invalid user role'),
            };
        }
        throw new Exception('Id not found');
    }
}
