<?php

namespace TestsHelpers\Auth\Login;

use Illuminate\Http\Request;
use TestsEnums\Status;

trait AuthLoginHelper
{
    public function testLoginUserSuccess(): void
    {
        $this->loginTest(Status::SUCCESS);
    }

    public function testLoginUserFailed(): void
    {
        $this->loginTest(Status::FAILED);;
    }

    private function loginTest($status): void
    {
        $this->register();
        match ($status) {
            Status::SUCCESS => $this->loginSuccessTest(),
            Status::FAILED => $this->loginFailsTest()
        };
    }

    private function loginSuccessTest(): void
    {
        $response = $this->login($this->credentials);
        $statusCode = $this->responseService->success()->getStatusCode();

        $this->assertEquals($statusCode, $response->getStatusCode());
        $this->assertArrayHasKey('token', $response->getData(true)['data']);
    }

    private function loginFailsTest(): void
    {
        $request = Request::create('/login', 'POST', [
            'email' => $this->credentials['email'],
            'password' => 'wrong_password',
            'role' => $this->credentials['role'],
        ]);

        $response = $this->registry->login($request);
        $statusCode = $this->responseService->unauthorized()->getStatusCode();

        $this->assertEquals($statusCode, $response->getStatusCode());
    }

    private function login(array $credentials): mixed
    {
        $request = Request::create('/login', 'POST', [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'role' => $credentials['role'],
        ]);

        return $this->registry->login($request);
    }
}
