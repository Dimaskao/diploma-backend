<?php

namespace TestsHelpers\Profile\Users\Specific\AdminHelpers;

use Illuminate\Http\JsonResponse;

trait AdminServiceHelper
{
    private function expectedRegularUserGetResult($response): void
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('profile', $response->getData(true));
    }
}
