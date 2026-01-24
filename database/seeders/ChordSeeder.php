<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $musicId = DB::table('musics')->value('id');
        $tone = 'C#';

        if (!$musicId) {
            return;
        }

        $content = 'Intro: Gm | Eb | Bb | F';
        $contentId = DB::table('chord_contents')->where('content', '=', $content)->value('id');

        if (!$contentId) {
            $now = now();
            $contentId = DB::table('chord_contents')->insertGetId([
                'content' => $content,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $exists = DB::table('chords')
            ->where('music_id', '=', $musicId)
            ->where('tone', '=', $tone)
            ->where('chord_content_id', '=', $contentId)
            ->exists();

        if ($exists) {
            return;
        }

        DB::table('chords')->insert([
            'tone' => $tone,
            'chord_content_id' => $contentId,
            'music_id' => $musicId,
            'version' => 'Guitar version in Gm',
        ]);
    }
}
