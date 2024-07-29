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
        $this->assertArrayHasKey('self', $responseData['data']['profile']);
        $this->assertArrayHasKey('another_admin_permissions', $responseData['data']['profile']);
        $this->assertArrayHasKey('ban_unban', $responseData['data']['profile']);
        $this->assertArrayHasKey('skills', $responseData['data']['profile']);
    }
}
