<?php

namespace TestsHelpers\Auth\Register;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait AuthRegisterHelper
{
    public function testRegisterUser(): void
    {
        $this->registerTest();
    }

    public function testRegisterValidationFails(): void
    {
        $request = Request::create('/register', 'POST', [
            'email' => 'invalid-email',
        ]);

        $response = $this->registry->register($request);
        $responseStatusCode = $this->responseService->badRequest()->getStatusCode();

        $this->assertEquals($responseStatusCode, $response->getStatusCode());
    }

    private function registerTest(): void
    {
        $registrationResultStatusCode = $this->register()->getStatusCode();
        $responseStatusCode = $this->responseService->created()->getStatusCode();

        $this->assertEquals($responseStatusCode, $registrationResultStatusCode);
        $this->assertDatabaseHas('users', ['email' => $this->credentials['email']]);
    }

    private function register(): JsonResponse
    {
        $request = Request::create('/register', 'POST', $this->credentials);
        return $this->registry->register($request);
    }
}
