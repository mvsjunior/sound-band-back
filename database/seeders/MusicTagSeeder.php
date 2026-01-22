<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MusicTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('musics_tags')->insertOrIgnore([
            ["music_id" => 1, "tag_id" => 1],
            ["music_id" => 1, "tag_id" => 2],
            ["music_id" => 1, "tag_id" => 3]
        ]);
    }
}
