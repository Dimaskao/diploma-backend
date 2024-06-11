<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Company;
use App\Traits\AuthTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use AuthTrait;

    /**
     * Register a new user or company.
     */
    public function register(Request $request)
    {
        $validator = $this->validateRegistrationData($request->all());

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $data = $validator->validated();
            $result = $this->registerUser($data);
            if ($data['role'] === 'company') {
                return response()->json(['company' => $result], 201);
            } else if ($data['role'] === 'user') {
                return response()->json(['user' => $result], 201);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create user or company'], 500);
        }
    }

    /**
     * Log in user or company.
     */
    public function login(Request $request): JsonResponse
    {
        $validator = $this->validateLoginData($request->all());

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $credentials = $request->only('email', 'password');
        $role = $request->input('role');

        $token = $this->userLogin($credentials, $role);
        if ($token) {
            return response()->json(['token' => $token], 200);
        }

        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    /**
     * Log out user or company.
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $this->userLogout($request->user());
            return response()->json(['message' => 'Successfully logged out'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to log out user'], 500);
        }
    }
}
