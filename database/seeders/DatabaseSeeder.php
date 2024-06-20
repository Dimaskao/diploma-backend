<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Passport\ClientRepository;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        DB::table('roles')->insert([
            'id' => (string) Str::uuid(),
            'name' => 'user',
        ]);

        // Create regular user
        $regularUserId = (string) Str::uuid();
        DB::table('regular_users')->insert([
            'id' => $regularUserId,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'skills_desc' => 'Programming',
            'experience' => '5 years',
        ]);

        // Get role and regular user
        $role = DB::table('roles')->where('name', 'user')->first();

        // Create user
        DB::table('users')->insert([
            'id' => (string) Str::uuid(),
            'role_id' => $role->id,
            'user_id' => $regularUserId,
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->createPersonalAccessClient();
    }

    private function createPersonalAccessClient()
    {
        $clientRepository = new ClientRepository();

        $client = $clientRepository->createPersonalAccessClient(
            null, 'Personal Access Client', 'http://localhost'
        );

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $client->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
