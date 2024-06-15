<?php

namespace Tests\Unit;

use App\Models\RegularUser;
use App\Models\Role;
use App\Models\User;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
//    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // increase memory limit
        ini_set('memory_limit', '-1');

//        if (DB::table('roles')->where('name', 'user')->get()->count() == 0) {
//            Role::create([
//                'name' => 'user'
//            ]);
//        }
//        if (DB::table('roles')->where('name', 'company')->get()->count() == 0) {
//            Role::create([
//                'name' => 'company'
//            ]);
//        }
    }

//    public function testRegisterUser()
//    {
//        $data = [
//            'first_name' => 'John',
//            'last_name' => 'Doe',
//            'email' => 'john.doe123@example.com',
//            'password' => 'password',
//            'password_confirmation' => 'password',
//            'role' => 'user'
//        ];
//
//        $response = $this->postJson('/api/register', $data);
//
//        $response->assertStatus(201)
//            ->assertJsonStructure([
//                'user' => [
//                    'id', 'email', 'role_id', 'created_at', 'updated_at', 'user_id'
//                ],
//                'regular_user' => [
//                    'id', 'first_name', 'last_name', 'created_at', 'updated_at'
//                ]
//            ]);
//
//        $this->assertDatabaseHas('users', ['email' => 'john.doe123@example.com']);
//    }
//
//    public function testRegisterCompany()
//    {
//        $data = [
//            'name' => 'Acme Corp',
//            'email' => 'contact@acme.com',
//            'password' => 'password',
//            'password_confirmation' => 'password',
//            'role' => 'company'
//        ];
//
//        $response = $this->postJson('/api/register', $data);
//
//        $response->assertStatus(201)
//            ->assertJsonStructure([
//                'user' => [
//                    'id', 'email', 'role_id', 'created_at', 'updated_at', 'company_id'
//                ],
//                'company' => [
//                    'id', 'name', 'contact_email', 'created_at', 'updated_at'
//                ]
//            ]);
//
//        $this->assertDatabaseHas('companies', ['contact_email' => 'contact@acme.com']);
//    }

//    public function testLoginUser()
//    {
////        $role = Role::where('name', 'user')->first();
////
////        $regularUser = RegularUser::create([
////            'first_name' => 'John',
////            'last_name' => 'Doe',
////        ]);
////
////
////        $user = User::create([
////            'email' => 'john.doe12345@example.com',
////            'password' => Hash::make('password'),
////            'role_id' => $role->id,
////            'user_id' => $regularUser->id,
////        ]);
//
//        $data = [
//            'email' => 'john.doe12345@example.com',
//            'password' => 'password',
//            'role' => 'user'
//        ];
//
//        $response = $this->postJson('/api/login', $data);
//
//        $response->assertStatus(200)
//            ->assertJsonStructure([
//                'token'
//            ]);
//    }

//    public function testLoginCompany()
//    {
////        $role = Role::where('name', 'company')->first();
////
////        $company = Company::create([
////            'name' => 'Acme Corp',
////            'contact_email' => 'contact@acme.com',
////        ]);
////
////        $user = User::create([
////            'email' => 'contact@acme.com',
////            'password' => Hash::make('password'),
////            'role_id' => $role->id,
////            'company_id' => $company->id,
////        ]);
//
//        $data = [
//            'email' => 'contact@acme.com',
//            'password' => 'password',
//            'role' => 'company'
//        ];
//
//        $response = $this->postJson('/api/login', $data);
//
//        $response->assertStatus(200)
//            ->assertJsonStructure([
//                'token'
//            ]);
//    }

//    public function testLogout()
//    {
//        $user = User::where('email', 'contact@acme.com')->first();
//
//        $tokenResult = $user->createToken('AppName');
//        $token = $tokenResult->accessToken;
//
//        $response = $this->postJson('/api/logout', [], [
//            'Authorization' => "Bearer $token"
//        ]);
//
//        $response->assertStatus(200)
//            ->assertJson([
//                'message' => 'Successfully logged out'
//            ]);
//    }
}
