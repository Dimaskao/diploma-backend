<?php

namespace Tests\Feature\FactoriesTests;

use App\Factories\UserFactory;
use App\Interfaces\Factory;
use App\Models\Company;
use App\Models\RegularUser;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class UserFactoryTest extends TestCase
{
    use RefreshDatabase;

    protected Factory $userFactory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userFactory = new UserFactory();
    }

    public function testCreateRegularUser()
    {
        $role = Role::create(['name' => 'user']);
        $params = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'role' => 'user',
        ];

        $result = $this->userFactory->create($params);

        $this->assertInstanceOf(User::class, $result['user']);
        $this->assertInstanceOf(RegularUser::class, $result['regular_user']);
        $this->assertEquals($params['email'], $result['user']->email);
        $this->assertTrue(password_verify($params['password'], $result['user']->password));
    }

    public function testCreateCompanyUser()
    {
        $role = Role::create(['name' => 'company']);
        $params = [
            'name' => 'Company Name',
            'email' => 'company@example.com',
            'password' => 'password123',
            'role' => 'company',
        ];

        $result = $this->userFactory->create($params);

        $this->assertInstanceOf(User::class, $result['user']);
        $this->assertInstanceOf(Company::class, $result['company']);
        $this->assertEquals($params['email'], $result['user']->email);
        $this->assertTrue(password_verify($params['password'], $result['user']->password));
    }

    public function testInvalidRole()
    {
        $this->expectException(\Exception::class);

        $params = [
            'email' => 'invalid@example.com',
            'password' => 'password123',
            'role' => 'invalid_role',
        ];

        $this->userFactory->create($params);
    }

    public function testMissingParams()
    {
        $this->expectException(\Exception::class);

        $params = [
            'email' => 'missing@example.com',
        ];

        $this->userFactory->create($params);
    }
}
