<?php

namespace Strategies\Profile\SpecificProfile;

use App\Enums\UserRole;
use App\Services\Profile\SpecificProfile\Company\CompanyProfileService;
use App\Strategies\Profile\SpecificProfile\CompanyProfileStrategy;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;
use TestsHelpers\Strategies\Profile\ProfileStrategyTestsHelper;

class CompanyProfileStrategyTest extends TestCase
{
    use ProfileStrategyTestsHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->service = new CompanyProfileService();
        $this->setUpProfileStrategy(new CompanyProfileStrategy($this->service), UserRole::COMPANY);
    }
}
