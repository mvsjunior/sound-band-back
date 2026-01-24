<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlaylistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('playlists')->insertOrIgnore([
            ['name' => 'Repertório Padrão', 'user_id' => 1],
            ['name' => 'Repertório Domingo', 'user_id' => 1],
            ['name' => 'Repertório Ceia', 'user_id' => 1]
        ]);
    }
}
