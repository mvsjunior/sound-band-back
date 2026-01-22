<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MusicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('musics')
            ->insertOrIgnore([
                [
                    "name" => "Nunca foi sobre nós", 
                    "artist" => "Ministério Zoe",
                    "lyrics_id" => 1,
                    "category_id" => 1,
                    "created_at" => new DateTime(),
                    "updated_at" => new DateTime()
                ]
            ]);
    }
}
