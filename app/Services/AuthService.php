<?php

namespace App\Services;

use App\Factories\UserFactory;
use App\Interfaces\Authenticator;
use App\Traits\AuthTrait;
use Exception;
use App\Interfaces\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthService
{
    use AuthTrait;

    protected Factory $factory;
    protected ValidationService $validationService;

    public function __construct(ValidationService $validationService, UserFactory $factory)
    {
        $this->validationService = $validationService;
        $this->factory = $factory;
    }

    /**
     * Register a new user or company.
     */
    public function register(Request $request): JsonResponse
    {
        $data = $this->validationService->validate($request->all(), $this->registrationRules());

        try {
            $result = $this->factory->create($data);
            return response()->json($result, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create user or company'], 500);
        }
    }

    /**
     * Log in user or company.
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $this->validationService->validate($request->all(), $this->loginRules());

            $credentials = $request->only('email', 'password');
            $role = $request->input('role');

            $token = $this->userLogin($credentials, $role);
            if ($token) {
                return response()->json(['token' => $token], 200);
            }
        } catch (Exception $e) {
            return response()->json(['error' => "Unauthenticated, {$e->getMessage()}"], 401);
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
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to log out user'], 500);
        }
    }

    protected function registrationRules(): array
    {
        return [
            'first_name' => 'required_if:role,user|string|max:255',
            'last_name' => 'required_if:role,user|string|max:255',
            'name' => 'required_if:role,company|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:user,company'
        ];
    }

    protected function loginRules(): array
    {
        return [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:user,company'
        ];
    }
}
