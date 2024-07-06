<?php

namespace App\Http\Controllers;

use App\Interfaces\Authentication;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected Authentication $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function register(Request $request) : JsonResponse
    {
        return $this->authentication->register($request);
    }

    public function login(Request $request) : JsonResponse
    {
        return $this->authentication->login($request);
    }

    public function logout(Request $request) : JsonResponse
    {
        return $this->authentication->logout($request);
    }
}
