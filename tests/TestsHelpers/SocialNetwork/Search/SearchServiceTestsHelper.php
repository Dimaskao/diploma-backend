<?php

namespace TestsHelpers\SocialNetwork\Search;

use TestsHelpers\SocialNetwork\ExpectedTestsResults;

trait SearchServiceTestsHelper
{
    use SearchServiceHelper, ExpectedTestsResults;

    public function testSearchUsers(): void
    {
        $response = $this->search($this->getTestRegularUsersSearchRequest());
        $this->expectedSearchUsersResult($response);
    }

    public function testSearchCompanies(): void
    {
        $response = $this->search($this->getTestCompaniesSearchRequest());
        $this->expectedSearchCompaniesResult($response);
    }
}
