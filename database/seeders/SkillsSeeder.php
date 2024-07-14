<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('skills')->insert([
            'id' => 1,
            'name' => '.Net Framework'
        ]);

        DB::table('skills')->insert([
            'id' => 2,
            'name' => 'Laravel'
        ]);

        DB::table('skills')->insert([
            'id' => 3,
            'name' => '.Net Core'
        ]);
    }
}
