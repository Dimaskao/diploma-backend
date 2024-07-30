<?php

namespace TestsHelpers\Controllers\Profile;

use TestsEnums\Method;
use TestsEnums\Status;

trait ProfileControllerTestsHelper
{
    use ProfileControllerHelper;

    public function testGetProfileSuccess(): void
    {
        $response = $this->controller->show($this->user->id);
        $this->expectedResult(Method::GET, Status::SUCCESS, $response);
    }

    public function testUpdateProfileSuccess(): void
    {
        $response = $this->controller->update($this->request, $this->user->id);
        $this->expectedResult(Method::UPDATE, Status::SUCCESS, $response);
    }

    public function testDeleteProfileSuccess(): void
    {
        $response = $this->controller->destroy($this->user->id);
        $this->expectedResult(Method::DELETE, Status::SUCCESS, $response);
    }
}
