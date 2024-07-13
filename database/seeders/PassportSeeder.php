<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PassportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $personalAccessSecret = 'abcd123abdc432';
        $passwordGrantSecret = 'qwd12gsgy46';

        // Insert the Personal Access Client
        DB::table('oauth_clients')->insert([
            'user_id' => null,
            'name' => 'Laravel Personal Access Client',
            'secret' => $personalAccessSecret,
            'provider' => null,
            'redirect' => 'http://localhost',
            'personal_access_client' => 1,
            'password_client' => 0,
            'revoked' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert the Password Grant Client
        DB::table('oauth_clients')->insert([
            'user_id' => null,
            'name' => 'Laravel Password Grant Client',
            'secret' => $passwordGrantSecret,
            'provider' => 'users',
            'redirect' => 'http://localhost',
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Get the id of the personal access client
        $clientId = DB::table('oauth_clients')->where('personal_access_client', 1)->value('id');

        // Insert the personal access client into the oauth_personal_access_clients table
        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => $clientId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
