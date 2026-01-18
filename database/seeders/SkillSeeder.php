<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('skills')->insertOrIgnore([
            ['name' => 'Vocal'],
            ['name' => 'Back Vocal'],
            ['name' => 'Guitarra'],
            ['name' => 'Violão'],
            ['name' => 'Baixo'],
            ['name' => 'Bateria'],
            ['name' => 'Teclado'],
        ]);
    }
}
