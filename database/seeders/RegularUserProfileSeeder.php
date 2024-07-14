<?php

namespace Database\Seeders;

use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class RegularUserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        (new UserFactory())->create([
            'first_name' =>  'regular_user_first_name',
            'last_name' => 'regular_user_last_name',
            'password' => '12345678',
            'email' => 'regular_user_test@test.com',
            'role' => 'user'
        ]);
    }
}
