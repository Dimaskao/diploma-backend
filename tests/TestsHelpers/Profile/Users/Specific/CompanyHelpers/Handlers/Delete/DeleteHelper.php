<?php

namespace TestsHelpers\Profile\Users\Specific\CompanyHelpers\Handlers\Delete;

use Illuminate\Http\JsonResponse;
use TestsEnums\Status;

trait DeleteHelper
{
    private function expectedCompanyDeleteResult($status, $response): void
    {
        match ($status) {
            Status::SUCCESS => $this->expectedCompanySuccessDeleteResult($response),
            Status::FAILED => $this->expectedCompanyFailedDeleteResult($response)
        };
    }

    private function expectedCompanySuccessDeleteResult($response): void
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Success', $response->getData(true)['message']);

        $this->assertDatabaseMissing('users', ['id' => $this->user->id]);
        $this->assertDatabaseMissing('companies', ['id' => $this->user->company_id]);
    }

    private function expectedCompanyFailedDeleteResult($response): void
    {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('User not found', $response->getData(true)['message']);
    }
}
