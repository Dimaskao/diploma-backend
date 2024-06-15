<?php

namespace App\Traits;

use App\Models\RegularUser;
use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\ClientRepository;

trait AuthTrait
{
    public function userLogin(array $credentials, string $role)
    {
        $user = User::where('email', $credentials['email'])->first();

        if ($user && ($role === 'user' || $role === 'company') && Hash::check($credentials['password'], $user->password)) {
            $clientRepository = new ClientRepository();
            $personalAccessClient = $clientRepository->personalAccessClient();

            if (!$personalAccessClient) {
                throw new \RuntimeException('Personal access client not found. Please create one.');
            }

            return $user->createToken('Personal Access Token', ['*'])->accessToken;
        }

        return false;
    }

    public function userLogout($user)
    {
        $user->token()->revoke();
        return true;
    }

    /**
     * @throws \Exception
     */
    public function registerUser(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        $role = Role::where('name', $data['role'])->first();

        if (!$role) {
            throw new \Exception('Invalid role');
        }

        $data['role_id'] = $role->id;

        if ($data['role'] === 'company') {
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
        } elseif ($data['role'] === 'user') {
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

        throw new \Exception('Failed to create user or company');
    }

    public function validateRegistrationData(array $data): \Illuminate\Validation\Validator
    {
        $validatedData = Validator::make($data, [
            'first_name' => 'required_if:role,user|string|max:255',
            'last_name' => 'required_if:role,user|string|max:255',
            'name' => 'required_if:role,company|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:user,company'
        ]);

        return $validatedData;
    }

    public function validateLoginData(array $data): \Illuminate\Validation\Validator
    {
        return Validator::make($data, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:user,company'
        ]);
    }
}
