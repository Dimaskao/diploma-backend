<?php

namespace TestsHelpers\Profile\Users\Specific\CompanyHelpers\Handlers\Get;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

trait GetHelper
{
    private function expectedCompanyGetResult($response): void
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $expectedStatusCode = $this->responseService->success()->status();

        $this->assertEquals($expectedStatusCode, $response->getStatusCode());

        $responseData = $response->getData(true);

        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('profile', $responseData['data']);
        $this->assertArrayHasKey('company', $responseData['data']['profile']);
        $this->assertArrayHasKey('posts', $responseData['data']['profile']);
        $this->assertArrayHasKey('job_offers', $responseData['data']['profile']);
        $this->assertArrayHasKey('name', $responseData['data']['profile']['company']);
        $this->assertArrayHasKey('description', $responseData['data']['profile']['company']);
        $this->assertArrayHasKey('contact_email', $responseData['data']['profile']['company']);
        $this->assertArrayHasKey('contact_phone', $responseData['data']['profile']['company']);
        $this->assertArrayHasKey('contact_url', $responseData['data']['profile']['company']);
    }
}
