<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tags')->insertOrIgnore([
            ['name' => 'Louvor'],
            ['name' => 'Clássico'],
            ['name' => 'Rápido'],
            ['name' => 'Lento'],
        ]);
    }
}
