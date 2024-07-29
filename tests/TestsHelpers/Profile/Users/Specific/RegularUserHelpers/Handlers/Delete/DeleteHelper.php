<?php

namespace TestsHelpers\Profile\Users\Specific\RegularUserHelpers\Handlers\Delete;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use TestsEnums\Status;

trait DeleteHelper
{
    private function expectedRegularUserDeleteResult($status, $response): void
    {
        match ($status) {
            Status::SUCCESS => $this->expectedRegularUserSuccessDeleteResult($response),
            Status::FAILED => $this->expectedRegularUserFailedDeleteResult($response)
        };
    }

    private function expectedRegularUserSuccessDeleteResult($response): void
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Success', $response->getData(true)['message']);

        $this->assertDatabaseMissing('users', ['id' => $this->user->id]);
        $this->assertDatabaseMissing('regular_users', ['id' => $this->user->user_id]);
    }

    private function expectedRegularUserFailedDeleteResult($response): void
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $responseData = $response->getData(true);
        Log::debug('**************************************** response delete data: ' . var_export($responseData, 1));

        $this->assertEquals(404, $response->getStatusCode());


        $this->assertEquals('User not found', $responseData['message']);
    }
}
