<?php

namespace TestsHelpers\Profile\Users\Specific\RegularUserHelpers\HttpMethods\Get;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

trait GetHelper
{
       private function expectedRegularUserGetResult($response): void
       {
           Log::debug('$response: ' . var_export($response, 1));

           $this->assertInstanceOf(JsonResponse::class, $response);
           $expectedStatusCode = $this->responseService->success()->status();

           $this->assertEquals($expectedStatusCode, $response->getStatusCode());

           $responseData = $response->getData(true);

           $this->assertArrayHasKey('data', $responseData);
           $this->assertArrayHasKey('profile', $responseData['data']);
           $this->assertArrayHasKey('user', $responseData['data']['profile']);
           $this->assertArrayHasKey('id', $responseData['data']['profile']['user']);
           $this->assertArrayHasKey('first_name', $responseData['data']['profile']['user']);
           $this->assertArrayHasKey('last_name', $responseData['data']['profile']['user']);
           $this->assertArrayHasKey('skills_desc', $responseData['data']['profile']['user']);
           $this->assertArrayHasKey('experience', $responseData['data']['profile']['user']);
           $this->assertArrayHasKey('education', $responseData['data']['profile']);
           $this->assertArrayHasKey('work_experience', $responseData['data']['profile']);
           $this->assertArrayHasKey('skills', $responseData['data']['profile']);
       }
}
