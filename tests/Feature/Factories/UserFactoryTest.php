<?php

namespace Tests\Feature\Factories;

use App\Factories\UserFactory;
use App\Interfaces\Factory;
use App\Models\Company;
use App\Models\RegularUser;
use App\Models\Role;
use App\Models\User;
use App\Services\Image\ImageProcessingService;
use App\Services\Image\ImageUploadService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use TestsHelpers\Factories\UserFactoryTestsHelper;

class UserFactoryTest extends TestCase
{
    use UserFactoryTestsHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->setUpUserFactory();
    }
}
