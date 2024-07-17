<?php

namespace App\Factories;

use App\Enums\UserRole;
use App\Interfaces\Factory;
use App\Models\Admin;
use App\Models\Company;
use App\Models\RegularUser;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserFactory implements Factory
{
    /**
     * @throws Exception
     */
    public function create($params = [])
    {
        if (isset($params['password']) && isset($params['role'])) {
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
        throw new Exception("Invalid params");
    }

    protected function createRegularUser(array $data): array
    {
        $regularUser = RegularUser::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
        ]);

        $user = User::create([
            'email' => $data['email'],
            'password' => $data['password'],
            'role_id' => $data['role_id'],
            'profileable_id' => $regularUser->id,
            'profileable_type' => RegularUser::class
        ]);

        return ['user' => $user, 'regular_user' => $regularUser];
    }

    protected function createCompanyUser(array $data): array
    {
        $company = Company::create([
            'name' => $data['name'],
            'contact_email' => $data['email'],
        ]);

        $user = User::create([
            'email' => $data['email'],
            'password' => $data['password'],
            'role_id' => $data['role_id'],
            'profileable_id' => $company->id,
            'profileable_type' => Company::class
        ]);

        return ['user' => $user, 'company' => $company];
    }

    protected function createAdminUser(array $data) : array
    {
        $admin = Admin::create([
            'name' => $data['name'],
            'permissions' => $data['permissions'],
        ]);

        $user = User::create([
            'email' => $data['email'],
            'password' => $data['password'],
            'role_id' => $data['role_id'],
            'profileable_id' => $admin->id,
            'profileable_type' => Admin::class,
        ]);

        return ['user' => $user, 'admin' => $admin];
    }
}
