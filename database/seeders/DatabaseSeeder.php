<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        ini_set('memory_limit', '-1');

        $this->call([
            PassportSeeder::class,
            RoleSeeder::class,
            SkillsSeeder::class,

//            RegularUserProfileSeeder::class,
//             add another seeders here
        ]);
    }
}
