<?php

namespace TestsHelpers\Strategies\Profile;

use TestsEnums\Method;
use TestsEnums\Status;
use TestsHelpers\Strategies\StrategyHelper;

trait ProfileStrategyTestsHelper
{
    use StrategyHelper;

    public function testGetProfileSuccess(): void
    {
        $response = $this->strategy->show($this->user->id);
        $this->expectedResult(Method::GET, Status::SUCCESS, $response);
    }

    public function testUpdateProfileSuccess(): void
    {
        $response = $this->strategy->update($this->request, $this->user->id);
        $this->expectedResult(Method::UPDATE, Status::SUCCESS, $response);
    }

    public function testDeleteProfileSuccess(): void
    {
        $response = $this->strategy->deleteProfile($this->user->id);
        $this->expectedResult(Method::DELETE, Status::SUCCESS, $response);
    }

    public function testDeleteProfileFailed(): void
    {
        $response = $this->strategy->deleteProfile('test_id');
        $this->expectedResult(Method::DELETE, Status::FAILED, $response);
    }
}
