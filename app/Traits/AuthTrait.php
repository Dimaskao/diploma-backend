<?php

namespace App\Traits;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\ClientRepository;

trait AuthTrait
{
    public function userLogin(array $credentials, string $role)
    {
        $user = User::where('email', $credentials['email'])->first();

        if ($user && ($role == UserRole::REGULAR_USER || $role == UserRole::COMPANY) && Hash::check($credentials['password'], $user->password)) {
            $personalAccessClient = (new ClientRepository())->personalAccessClient();

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
}
