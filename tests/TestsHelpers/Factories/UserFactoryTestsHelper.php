<?php

namespace TestsHelpers\Factories;

use App\Models\Company;
use App\Models\RegularUser;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use TestsHelpers\Auth\AuthHelper;
use TestsHelpers\Image\Helpers\ImageUploadSetUpHelper;
use TestsHelpers\Profile\Users\UsersHelper;

trait UserFactoryTestsHelper
{
    use RefreshDatabase, ImageUploadSetUpHelper, AuthHelper, UsersHelper, UserFactoryHelper;

    public function testCreateRegularUser(): void
    {
        $params = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
            'role' => 'user',
            'avatar' => $this->getTestUploadedFile()
        ];

        Log::debug('$params: ' . var_export($params, 1));

        $result = $this->userFactory->create($params);

        Log::debug('$result: ' . var_export($result, 1));

        $this->assertInstanceOf(User::class, $result['user']);
        $this->assertInstanceOf(RegularUser::class, $result['regular_user']);
        $this->assertEquals($params['email'], $result['user']->email);
        $this->assertTrue(password_verify($params['password'], $result['user']->password));
    }
//
//    public function testCreateCompanyUser(): void
//    {
//        $params = [
//            'name' => 'Company Name',
//            'email' => 'company@example.com',
//            'password' => 'password123',
//            'role' => 'company',
//            'avatar' => $this->getTestUploadedFile()
//        ];
//
//        $result = $this->userFactory->create($params);
//
//        $this->assertInstanceOf(User::class, $result['user']);
//        $this->assertInstanceOf(Company::class, $result['company']);
//        $this->assertEquals($params['email'], $result['user']->email);
//        $this->assertTrue(password_verify($params['password'], $result['user']->password));
//    }
//
//    public function testInvalidRole(): void
//    {
//        $this->expectException(Exception::class);
//
//        $params = [
//            'email' => 'invalid@example.com',
//            'password' => 'password123',
//            'role' => 'invalid_role',
//        ];
//
//        $this->userFactory->create($params);
//    }
//
//    public function testMissingParams(): void
//    {
//        $this->expectException(Exception::class);
//
//        $params = [
//            'email' => 'missing@example.com',
//        ];
//
//        $this->userFactory->create($params);
//    }
}
