<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LyricsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('lyrics')
            ->insertOrIgnore([
                ['content' => "Uma letra qualquer ". PHP_EOL . "Mais uma linha"]
            ]
        );
    }
}
