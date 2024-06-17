<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class ProfileControllerTest extends TestCase
{
//    use RefreshDatabase;

//    protected function setUp(): void
//    {
//        parent::setUp();
//
//        // Run the database migrations
////        $this->artisan('migrate');
////
////        // Log to confirm migrations
////        Log::info('Database migrated');
////
////        // Seed the database
////        $this->seed();
////
////        // Log to confirm seeding
////        Log::info('Database seeded');
//    }

    /**
     * Test the show method for a user.
     */
//    public function testShowMethodForUser()
//    {
//        // Ensure the roles table is not empty
//        if (DB::table('roles')->count() == 0) {
//            DB::table('roles')->insert([
//                'id' => '9c4b6516-b557-4c9c-8b6b-b3d9f56050c9',
//                'name' => 'user'
//            ]);
//        }
//
//        // Ensure the regular_users table is not empty
//        if (DB::table('regular_users')->count() == 0) {
//            DB::table('regular_users')->insert([
//                'id' => '9c4b6517-467a-4152-8c0b-00bf1dca0c78',
//                'first_name' => 'John',
//                'last_name' => 'Doe',
//                'skills_desc' => 'Programming',
//                'experience' => '5 years'
//            ]);
//        }
//
//        $userId = DB::table('regular_users')->where('first_name', 'John')->first()->id;
//        $roleId = DB::table('roles')->where('name', 'user')->first()->id;
//
//        // Create an authenticated user with a unique email using Eloquent
//        $uniqueEmail = 'user1_' . uniqid() . '@example.com';
//        $user = User::create([
//            'role_id' => $roleId,
//            'email' => $uniqueEmail,
//            'password' => bcrypt('password'),
//            'user_id' => $userId
//        ]);
//
//        // Authenticate the user
//        $this->actingAs($user, 'api');
//
//        // Log the user being requested
//        Log::info('Requesting profile for user ID: ' . $user->id);
//
//        // Perform the request
//        $response = $this->get("/api/profile/{$user->id}");
//
//        // Log the response content
//        Log::info('Profile show response', ['content' => $response->getContent()]);
//
//        // Assert the response
//        $response->assertStatus(200)
//            ->assertJson([
//                'user' => [
//                    'firstName' => 'John',
//                    'lastName' => 'Doe',
//                    'skillsDesc' => null,
//                    'experience' => null
//                ],
//                'education' => [],
//                'workExperience' => [],
//                'skills' => []
//            ]);
//    }


    /**
     * Test the show method for a company.
     */
//    public function testShowMethodForCompany()
//    {
//        // Ensure the roles table is not empty
//        if (DB::table('roles')->where('name', 'company')->count() == 0) {
//            DB::table('roles')->insert([
//                'name' => 'company'
//            ]);
//        }
//
//        // Create a company in the database
//        if (DB::table('companies')->count() == 0) {
//            DB::table('companies')->insert([
//                'name' => 'Acme Corp',
//                'description' => 'A leading company',
//                'contact_email' => 'contact@acme.com',
//                'contact_phone' => '1234567890',
//                'contact_url' => 'http://acme.com'
//            ]);
//        }
//
//        $companyId = DB::table('companies')->where('name', 'Acme Corp')->first()->id;
//        $roleId = DB::table('roles')->where('name', 'company')->first()->id;
//
//        // Create an authenticated user with a unique email using Eloquent
//        $uniqueEmail = 'company_' . uniqid() . '@example.com';
//        $user = User::create([
//            'role_id' => $roleId,
//            'email' => $uniqueEmail,
//            'password' => bcrypt('password'),
//            'company_id' => $companyId
//        ]);
//
//        // Authenticate the user
//        $this->actingAs($user, 'api');
//
//        // Log the user being requested
//        Log::info('Requesting profile for company ID: ' . $user->id);
//
//        // Perform the request
//        $response = $this->get("/api/profile/{$user->id}");
//
//        // Log the response content
//        Log::info('Profile show response', ['content' => $response->getContent()]);
//
//        // Assert the response
//        $response->assertStatus(200)
//            ->assertJson([
//                'id' => $user->id,
//                'name' => 'Acme Corp',
//                'description' => 'A leading company',
//                'contactEmail' => 'contact@acme.com',
//                'contactPhone' => '1234567890',
//                'contactUrl' => 'http://acme.com',
//                'posts' => [],
//                'jobOffers' => []
//            ]);
//    }

    /**
     * Test the update method for a user.
     */
    public function testUpdateMethodForUser()
    {
//        // Ensure the roles table is not empty
//        if (DB::table('roles')->where('name', 'user')->count() == 0) {
//            DB::table('roles')->insert([
//                'id' => '9c4b6516-b557-4c9c-8b6b-b3d9f56050c9',
//                'name' => 'user'
//            ]);
//        }
//
//        // Create a user in the database
//        if (DB::table('users')->where('email', 'user@example.com')->count() == 0) {
//            DB::table('users')->insert([
//                'id' => '9c4b773e-37a6-4792-b375-a40c9030f21a',
//                'role_id' => '9c4b6516-b557-4c9c-8b6b-b3d9f56050c9',
//                'email' => 'user@example.com',
//                'password' => bcrypt('password')
//            ]);
//        }
//
//        // Create a regular user in the database
//        if (DB::table('regular_users')->where('id', '9c4b773e-37a6-4792-b375-a40c9030f21a')->count() == 0) {
//            DB::table('regular_users')->insert([
//                'id' => '9c4b773e-37a6-4792-b375-a40c9030f21a',
//                'user_id' => '9c4b773e-37a6-4792-b375-a40c9030f21a',
//                'first_name' => 'John',
//                'last_name' => 'Doe',
//                'skills_desc' => 'Programming',
//                'experience' => '5 years'
//            ]);
//        }

        // Authenticate the user
        $user = User::find('9c4b6518-02dc-42d6-8b9a-b7f7481e9aa0');
        $this->actingAs($user, 'api');

        // Perform the update request
//        $response = $this->put("/api/profile/{$user->id}", [
//            'updateType' => [
//                'personalInformation' => [
//                    'first_name' => 'Jane',
//                    'last_name' => 'Doe',
//                    'skills_desc' => 'Design',
//                    'experience' => '3 years'
//                ]
//            ]
//        ]);
//
//        // Assert the response
//        $response->assertStatus(200)
//            ->assertJson([
//                'message' => 'Profile updated successfully',
//                'user' => [
//                    'first_name' => 'Jane',
//                    'last_name' => 'Doe',
//                    'skills_desc' => 'Design',
//                    'experience' => '3 years'
//                ]
//            ]);
//
//        Log::info('regular_users_result: ' . var_export([
//                'id' => $user->user_id,
//                'first_name' => 'Jane',
//                'last_name' => 'Doe',
//                'skills_desc' => 'Design',
//                'experience' => '3 years'
//            ], 1 ));
//        // Ensure the database has been updated correctly
//        $this->assertDatabaseHas('regular_users', [
//            'id' => $user->user_id,
//            'first_name' => 'Jane',
//            'last_name' => 'Doe',
//            'skills_desc' => 'Design',
//            'experience' => '3 years'
//        ]);

        $response = $this->put("/api/profile/{$user->id}", [
            'updateType' => [
                'workExperience' => [
                    'position' => 'Software developer',
                    'company' => 'Independent contractor',
                    'description' => 'Worked as a freelancer'
                ]
            ]
        ]);

        // Assert the response
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Profile updated successfully',
                'user' => [
                    'first_name' => 'Jane',
                    'last_name' => 'Doe',
                    'skills_desc' => 'Design',
                    'experience' => '3 years'
                ]
            ]);

        Log::info('regular_users_result: ' . var_export([
                'id' => $user->user_id,
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'skills_desc' => 'Design',
                'experience' => '3 years'
            ], 1 ));
        // Ensure the database has been updated correctly
        $this->assertDatabaseHas('regular_users', [
            'id' => $user->user_id,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'skills_desc' => 'Design',
            'experience' => '3 years'
        ]);
    }

//    /**
//     * Test the subscribe method.
//     */
//    public function testSubscribeMethod()
//    {
//        // Create a subscriber and a subscription in the database
//        DB::table('roles')->insert([
//            'id' => 1,
//            'name' => 'user'
//        ]);
//
//        DB::table('users')->insert([
//            'id' => 1,
//            'role_id' => 1,
//            'email' => 'user1@example.com',
//            'password' => bcrypt('password')
//        ]);
//
//        DB::table('users')->insert([
//            'id' => 2,
//            'role_id' => 1,
//            'email' => 'user2@example.com',
//            'password' => bcrypt('password')
//        ]);
//
//        DB::table('regular_users')->insert([
//            'id' => 1,
//            'user_id' => 1,
//        ]);
//
//        DB::table('regular_users')->insert([
//            'id' => 2,
//            'user_id' => 2,
//        ]);
//
//        $response = $this->post("/subscribe", [
//            'subscriberId' => 1,
//            'subscriptionId' => 2
//        ]);
//
//        $response->assertStatus(200)
//            ->assertJson(['message' => 'Subscribed successfully']);
//    }
//
//    /**
//     * Test the unsubscribe method.
//     */
//    public function testUnsubscribeMethod()
//    {
//        // Create a subscriber and a subscription in the database
//        DB::table('roles')->insert([
//            'id' => 1,
//            'name' => 'user'
//        ]);
//
//        DB::table('users')->insert([
//            'id' => 1,
//            'role_id' => 1,
//            'email' => 'user1@example.com',
//            'password' => bcrypt('password')
//        ]);
//
//        DB::table('users')->insert([
//            'id' => 2,
//            'role_id' => 1,
//            'email' => 'user2@example.com',
//            'password' => bcrypt('password')
//        ]);
//
//        DB::table('regular_users')->insert([
//            'id' => 1,
//            'user_id' => 1,
//        ]);
//
//        DB::table('regular_users')->insert([
//            'id' => 2,
//            'user_id' => 2,
//        ]);
//
//        DB::table('user_contacts')->insert([
//            'subscriber_id' => 1,
//            'subscription_id' => 2,
//        ]);
//
//        $response = $this->delete("/unsubscribe", [
//            'subscriberId' => 1,
//            'subscriptionId' => 2
//        ]);
//
//        $response->assertStatus(200)
//            ->assertJson(['message' => 'Unsubscribed successfully']);
//    }
//
//    /**
//     * Test the search method.
//     */
//    public function testSearchMethod()
//    {
//        // Create users and companies in the database
//        DB::table('roles')->insert([
//            ['id' => 1, 'name' => 'user'],
//            ['id' => 2, 'name' => 'company']
//        ]);
//
//        DB::table('users')->insert([
//            ['id' => 1, 'role_id' => 1, 'email' => 'user@example.com', 'password' => bcrypt('password')],
//            ['id' => 2, 'role_id' => 2, 'email' => 'company@example.com', 'password' => bcrypt('password')]
//        ]);
//
//        DB::table('regular_users')->insert([
//            'id' => 1,
//            'user_id' => 1,
//            'first_name' => 'John',
//            'last_name' => 'Doe'
//        ]);
//
//        DB::table('companies')->insert([
//            'id' => 2,
//            'company_id' => 2,
//            'name' => 'Acme Corp'
//        ]);
//
//        $response = $this->get('/search?query=John&users=true');
//
//        $response->assertStatus(200)
//            ->assertJson([
//                'results' => [
//                    [
//                        'id' => 1,
//                        'name' => 'John Doe'
//                    ]
//                ]
//            ]);
//
//        $response = $this->get('/search?query=Acme&companies=true');
//
//        $response->assertStatus(200)
//            ->assertJson([
//                'results' => [
//                    [
//                        'id' => 2,
//                        'name' => 'Acme Corp'
//                    ]
//                ]
//            ]);
//    }
}
