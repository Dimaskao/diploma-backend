<?php

namespace Strategies\Profile\SpecificProfile;

use App\Enums\UserRole;
use App\Services\Profile\SpecificProfile\Admin\AdminProfileService;
use App\Strategies\Profile\SpecificProfile\AdminProfileStrategy;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;
use TestsHelpers\Strategies\Profile\ProfileStrategyTestsHelper;

class AdminProfileStrategyTest extends TestCase
{
    use ProfileStrategyTestsHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->service = new AdminProfileService();
        $this->setUpProfileStrategy(new AdminProfileStrategy($this->service), UserRole::ADMIN);
    }
}
