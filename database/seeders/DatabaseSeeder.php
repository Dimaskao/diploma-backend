<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RegularUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
//use Laravel\Passport\ClientRepository;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        ini_set('memory_limit', '-1');
        $this->createRoles();
        $this->createPersonalAccessClient();
//        $this->createUsers();
    }

    private function createRoles()
    {
        $roles = ['user', 'company'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }

    private function createPersonalAccessClient()
    {
        $seeder = new PassportSeeder();
        $seeder->run();
    }

    private function createUsers()
    {
        $role = Role::where('name', 'user')->first();

        $regularUser = RegularUser::firstOrCreate([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        User::firstOrCreate([
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password123'),
            'role_id' => $role->id,
            'user_id' => $regularUser->id,
        ]);
    }
}
