<?php

namespace App\Factories;

use App\Enums\UserRole;
use App\Interfaces\Factory;
use App\Models\Admin;
use App\Models\Company;
use App\Models\RegularUser;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserFactory implements Factory
{
    protected const REGULAR_USER_ID = 'regular_user_id';
    protected const COMPANY_ID = 'company_id';
    protected const ADMIN_ID = 'admin_id';

    /**
     * @throws Exception
     */
    public function create($params = []): array
    {
        if (!isset($params['password'], $params['role'])) {
            throw new Exception("Invalid params");
        }

        $params['password'] = Hash::make($params['password']);
        $role = Role::where('name', $params['role'])->firstOrFail();
        $params['role_id'] = $role->id;

        return match ($params['role']) {
            UserRole::REGULAR_USER => $this->createRegularUser($params),
            UserRole::COMPANY => $this->createCompanyUser($params),
            UserRole::ADMIN => $this->createAdminUser($params),
            default => throw new Exception('Invalid role'),
        };
    }

    protected function createRegularUser(array $data): array
    {
        $regularUser = RegularUser::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ]);
        $user = $this->createBaseUser($data, UserFactory::REGULAR_USER_ID, $regularUser);

        return ['user' => $user, 'regular_user' => $regularUser];
    }

    protected function createCompanyUser(array $data): array
    {
        $company = Company::create([
            'name' => $data['name'],
            'contact_email' => $data['email'],
        ]);
        $user = $this->createBaseUser($data, UserFactory::COMPANY_ID, $company);

        return ['user' => $user, 'company' => $company];
    }

    protected function createAdminUser(array $data): array
    {
        $admin = Admin::create([
            'name' => $data['name'],
            'permissions' => json_encode($data['permissions']),
        ]);
        $user = $this->createBaseUser($data, UserFactory::ADMIN_ID, $admin);

        return ['user' => $user, 'admin' => $admin];
    }

    private function profileData($data, $key, $specificUser)
    {
        $profile = UserProfile::create([$key => $specificUser->id]);
        $data['user_profile_id'] = $profile->id;
        return $data;
    }

    private function createBaseUser($data, $key, $specificUser)
    {
        $data = $this->profileData($data, $key, $specificUser);
        return User::create([
            'email' => $data['email'],
            'password' => $data['password'],
            'role_id' => $data['role_id'],
            'user_profile_id' => $data['user_profile_id']
        ]);
    }
}
