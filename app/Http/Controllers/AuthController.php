<?php

namespace App\Http\Controllers;

use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function register(Request $request): JsonResponse
    {
        return $this->service->register($request);
    }

    public function login(Request $request): JsonResponse
    {
        return $this->service->login($request);
    }

    public function logout(Request $request): JsonResponse
    {
        return $this->service->logout($request);
    }
}
