<?php

namespace TestsHelpers\Auth\Login;

use Illuminate\Http\Request;
use TestsEnums\Status;

trait AuthLoginTestsHelper
{
    public function testLoginUserSuccess(): void
    {
        $this->refreshCredentials();
        $this->loginTest(Status::SUCCESS);
    }

    public function testLoginUserFailed(): void
    {
        $this->refreshCredentials();
        $this->loginTest(Status::FAILED);;
    }
}
