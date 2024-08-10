<?php

namespace TestsHelpers\Auth\Logout;

use App\Models\User;
use Illuminate\Http\Request;
use TestsEnums\Status;

trait AuthLogoutTestsHelper
{
    public function testLogoutSuccess(): void
    {
        $this->refreshCredentials();
        $this->logoutTest(Status::SUCCESS);
    }

    public function testLogoutFailed(): void
    {
        $this->refreshCredentials();
        $this->logoutTest(Status::FAILED);
    }
    private function logoutTest(string $status): void
    {
        match ($status) {
            Status::SUCCESS => $this->logoutSuccessTest(),
            Status::FAILED => $this->logoutFailedTest()
        };
    }

    private function logoutSuccessTest(): void
    {
        $this->register();
        $this->login($this->credentials);
        $this->logoutUserSuccess($this->credentials['email']);
    }

    private function logoutFailedTest(): void
    {
        $this->register();
        $this->logoutFailed();
    }
}
