<?php

namespace TestsHelpers\Profile\Users\Specific\AdminHelpers\Handlers\Delete;

use Illuminate\Http\JsonResponse;
use TestsEnums\Status;

trait DeleteHelper
{
    private function expectedAdminDeleteResult($status, $response): void
    {
        match ($status) {
            Status::SUCCESS => $this->expectedAdminSuccessDeleteResult($response),
            Status::FAILED => $this->expectedAdminFailedDeleteResult($response)
        };
    }

    private function expectedAdminSuccessDeleteResult($response): void
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Success', $response->getData(true)['message']);

        $this->assertDatabaseMissing('users', ['id' => $this->user->id]);
        $this->assertDatabaseMissing('admins', ['id' => $this->user->admin_id]);
    }

    private function expectedAdminFailedDeleteResult($response): void
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('User not found', $response->getData(true)['message']);
    }
}
