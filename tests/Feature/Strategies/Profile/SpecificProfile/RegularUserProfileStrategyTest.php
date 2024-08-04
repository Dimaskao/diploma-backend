<?php

namespace Strategies\Profile\SpecificProfile;

use App\Enums\UserRole;
use App\Services\Profile\SpecificProfile\RegularUser\RegularUserProfileService;
use App\Strategies\Profile\SpecificProfile\RegularUserProfileStrategy;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;
use TestsHelpers\Strategies\Profile\ProfileStrategyTestsHelper;

class RegularUserProfileStrategyTest extends TestCase
{
    use ProfileStrategyTestsHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->service = new RegularUserProfileService();
        $this->setUpProfileStrategy(new RegularUserProfileStrategy($this->service), UserRole::REGULAR_USER);
    }
}
