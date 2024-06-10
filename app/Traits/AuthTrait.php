<?php

namespace App\Traits;

use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

trait AuthTrait
{
    public function userLogin(array $credentials, string $role)
    {
        if ($role === 'user' && Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')->user();
            return $user->createToken('AppName')->accessToken;
        } elseif ($role === 'company' && Auth::guard('company')->attempt($credentials)) {
            $company = Auth::guard('company')->user();
            return $company->createToken('AppName')->accessToken;
        }

        return false;
    }

    public function userLogout($user)
    {
        $user->token()->revoke();
        return true;
    }

    public function registerUser(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        $role = Role::where('name', $data['role'])->first();

        if (!$role) {
            throw new \Exception('Invalid role');
        }

        $data['role_id'] = $role->id;

        if ($data['role'] === 'company') {
            return Company::create($data);
        } else if ($data['role'] === 'user') {
            return User::create($data);
        }

        throw new \Exception('Failed to create user or company');
    }

    public function validateRegistrationData(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required_if:role,user|string|max:255',
            'last_name' => 'required_if:role,user|string|max:255',
            'name' => 'required_if:role,company|string|max:255',
            'email' => 'required|string|email|unique:users|unique:companies|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:user,company'
        ]);
    }

    public function validateLoginData(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:user,company'
        ]);
    }
}
