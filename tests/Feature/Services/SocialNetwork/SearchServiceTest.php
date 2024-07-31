<?php

namespace Services\SocialNetwork;

use App\Enums\UserRole;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;
use TestsHelpers\SocialNetwork\Search\SearchServiceTestsHelper;

class SearchServiceTest extends TestCase
{
    use SearchServiceTestsHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->setUpSearchService(UserRole::REGULAR_USER);
    }
}
