<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the show method.
     *
     * @return void
     */
    public function testShowMethodForUser()
    {
        // Create a user and their profile in the database
        $user = DB::table('users')->insertGetId([
            'id' => 1,
            'role' => 'user'
        ]);

        DB::table('regular_users')->insert([
            'id' => 1,
            'user_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'skills_desc' => 'Programming',
            'experience' => '5 years'
        ]);

        $response = $this->get("/profile/1");

        $response->assertStatus(200)
            ->assertJson([
                'user' => [
                    'firstName' => 'John',
                    'lastName' => 'Doe',
                    'skillsDesc' => 'Programming',
                    'experience' => '5 years'
                ]
            ]);
    }

    public function testShowMethodForCompany()
    {
        // Create a company and their profile in the database
        $user = DB::table('users')->insertGetId([
            'id' => 2,
            'role' => 'company'
        ]);

        DB::table('companies')->insert([
            'id' => 2,
            'company_id' => 2,
            'name' => 'Acme Corp',
            'description' => 'A leading company',
            'contact_email' => 'contact@acme.com',
            'contact_phone' => '1234567890',
            'contact_url' => 'http://acme.com'
        ]);

        $response = $this->get("/profile/2");

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Acme Corp',
                'description' => 'A leading company',
                'contactEmail' => 'contact@acme.com',
                'contactPhone' => '1234567890',
                'contactUrl' => 'http://acme.com'
            ]);
    }

    /**
     * Test the update method.
     *
     * @return void
     */
    public function testUpdateMethodForUser()
    {
        // Create a user and their profile in the database
        $user = DB::table('users')->insertGetId([
            'id' => 1,
            'role' => 'user'
        ]);

        DB::table('regular_users')->insert([
            'id' => 1,
            'user_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'skills_desc' => 'Programming',
            'experience' => '5 years'
        ]);

        $response = $this->put("/profile/1", [
            'updateType' => [
                'personalInformation' => [
                    'first_name' => 'Jane',
                    'last_name' => 'Doe',
                    'skills_desc' => 'Design',
                    'experience' => '3 years'
                ]
            ]
        ]);

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
    }

    /**
     * Test the subscribe method.
     *
     * @return void
     */
    public function testSubscribeMethod()
    {
        // Create a subscriber and a subscription in the database
        $subscriberId = DB::table('users')->insertGetId([
            'id' => 1,
            'role' => 'user'
        ]);

        $subscriptionId = DB::table('users')->insertGetId([
            'id' => 2,
            'role' => 'user'
        ]);

        DB::table('regular_users')->insert([
            'id' => 1,
            'user_id' => 1,
        ]);

        DB::table('regular_users')->insert([
            'id' => 2,
            'user_id' => 2,
        ]);

        $response = $this->post("/subscribe", [
            'subscriberId' => 1,
            'subscriptionId' => 2
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Subscribed successfully']);
    }

    /**
     * Test the search method.
     *
     * @return void
     */
    public function testSearchMethod()
    {
        // Create users and companies in the database
        DB::table('users')->insert([
            ['id' => 1, 'role' => 'user'],
            ['id' => 2, 'role' => 'company']
        ]);

        DB::table('regular_users')->insert([
            'id' => 1,
            'user_id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);

        DB::table('companies')->insert([
            'id' => 2,
            'name' => 'Acme Corp'
        ]);

        $response = $this->get('/search', [
            'query' => 'John',
            'users' => true
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'results' => [
                    [
                        'id' => 1,
                        'name' => 'John Doe'
                    ]
                ]
            ]);

        $response = $this->get('/search', [
            'query' => 'Acme',
            'companies' => true
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'results' => [
                    [
                        'id' => 2,
                        'name' => 'Acme Corp'
                    ]
                ]
            ]);
    }
}
