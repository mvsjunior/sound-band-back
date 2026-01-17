<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
        $tones = [
            ['name' => 'C',  'type' => 'major'],
            ['name' => 'C#', 'type' => 'major'],
            ['name' => 'D',  'type' => 'major'],
            ['name' => 'Eb', 'type' => 'major'],
            ['name' => 'E',  'type' => 'major'],
            ['name' => 'F',  'type' => 'major'],
            ['name' => 'F#', 'type' => 'major'],
            ['name' => 'G',  'type' => 'major'],
            ['name' => 'Ab', 'type' => 'major'],
            ['name' => 'A',  'type' => 'major'],
            ['name' => 'Bb', 'type' => 'major'],
            ['name' => 'B',  'type' => 'major'],

            ['name' => 'Cm',  'type' => 'minor'],
            ['name' => 'C#m', 'type' => 'minor'],
            ['name' => 'Dm',  'type' => 'minor'],
            ['name' => 'Ebm', 'type' => 'minor'],
            ['name' => 'Em',  'type' => 'minor'],
            ['name' => 'Fm',  'type' => 'minor'],
            ['name' => 'F#m', 'type' => 'minor'],
            ['name' => 'Gm',  'type' => 'minor'],
            ['name' => 'Abm', 'type' => 'minor'],
            ['name' => 'Am',  'type' => 'minor'],
            ['name' => 'Bbm', 'type' => 'minor'],
            ['name' => 'Bm',  'type' => 'minor'],
        ];

        DB::table('tones')->insertOrIgnore($tones);
    }
}
