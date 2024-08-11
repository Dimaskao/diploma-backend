<?php

namespace TestsHelpers\Auth\Register;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait AuthRegisterHelper
{

    public function registerUser(): JsonResponse
    {
        return $this->register();
    }

    private function register(): JsonResponse
    {
        $request = Request::create('/register', 'POST', $this->credentials);
        return $this->registry->register($request);
    }
}
