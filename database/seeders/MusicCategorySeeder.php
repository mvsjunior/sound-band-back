<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MusicCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('music_categories')->insertOrIgnore([
            ['name' => 'Adoração'],
            ['name' => 'Celebração'],
            ['name' => 'Família'],
            ['name' => 'Reconciliação'],
        ]);
    }
}
