<?php

namespace TestsHelpers\Profile\Users\Specific\AdminHelpers\Handlers\Get;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

trait GetHelper
{
    private function expectedAdminGetResult($response): void
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $expectedStatusCode = $this->responseService->success()->status();

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());

        $responseData = $response->getData(true);

        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('profile', $responseData['data']);
        $this->assertArrayHasKey('admin', $responseData['data']['profile']);
        $this->assertArrayHasKey('id', $responseData['data']['profile']['admin']);
        $this->assertArrayHasKey('name', $responseData['data']['profile']['admin']);
        $this->assertArrayHasKey('permissions', $responseData['data']['profile']['admin']);
        $this->assertArrayHasKey('email', $responseData['data']['profile']['admin']);
        $this->assertArrayHasKey('avatar_url', $responseData['data']['profile']['admin']);
    }
}
