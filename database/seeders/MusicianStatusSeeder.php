<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MusicianStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('musician_statuses')->insertOrIgnore([
            ['name' => 'active'],
            ['name' => 'inactive'],
            ['name' => 'absent'],
        ]);
    }
}
