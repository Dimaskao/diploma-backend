<?php

namespace App\Factories;

use App\Enums\UserRole;
use App\Interfaces\Factory;
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
            'user_id' => $regularUser->id,
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
            'company_id' => $company->id,
        ]);

        return ['user' => $user, 'company' => $company];
    }
}
