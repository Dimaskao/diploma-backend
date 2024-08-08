<?php

namespace Tests\Feature\Factories;

use App\Enums\UserRole;
use App\Factories\ProfileStrategyFactory;
use App\Interfaces\Factory;
use App\Models\Role;
use App\Models\User;
use App\Strategies\Profile\SpecificProfile\CompanyProfileStrategy;
use App\Strategies\Profile\SpecificProfile\RegularUserProfileStrategy;
use Database\Seeders\DatabaseSeeder;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileStrategyFactoryTest extends TestCase
{
    use RefreshDatabase;

    protected Factory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->factory = new ProfileStrategyFactory();
    }

    public function testCreateRegularUserProfileStrategy()
    {
        $user = User::factory()->create([
            'role_id' => Role::where('name', UserRole::REGULAR_USER)->first()->id,
        ]);

        $params = ['id' => $user->id];
        $strategy = $this->factory->create($params);

        $this->assertInstanceOf(RegularUserProfileStrategy::class, $strategy);
    }

    public function testCreateCompanyProfileStrategy()
    {
        $user = User::factory()->create([
            'role_id' => Role::where('name', UserRole::COMPANY)->first()->id,
        ]);

        $params = ['id' => $user->id];
        $strategy = $this->factory->create($params);

        $this->assertInstanceOf(CompanyProfileStrategy::class, $strategy);
    }

    public function testCreateThrowsExceptionForInvalidUserRole()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid user role');

        $role = Role::create(['name' => 'invalid_role']);
        $user = User::factory()->create(['role_id' => $role->id]);

        $params = ['id' => $user->id];
        $this->factory->create($params);
    }

    public function testCreateThrowsExceptionForUserNotFound()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('User not found');

        $params = ['id' => 'non_existing_id'];
        $this->factory->create($params);
    }

    public function testCreateThrowsExceptionForIdNotFound()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Id not found');

        $this->factory->create();
    }
}
