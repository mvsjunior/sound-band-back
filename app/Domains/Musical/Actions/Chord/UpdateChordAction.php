<?php

namespace App\Domains\Musical\Actions\Chord;

use App\Domains\Musical\Database\Models\Chord;
use Illuminate\Support\Facades\DB;

class UpdateChordAction
{
    public function execute(int $id, int $musicId, string $tone, string $content, string $version): void
    {
        DB::transaction(function () use ($id, $musicId, $tone, $content, $version) {
            $chord = Chord::query()->select(['id', 'chord_content_id'])->find($id);
            if (!$chord) {
                return;
            }

            $chord->update([
                'music_id' => $musicId,
                'tone' => $tone,
                'version' => $version,
            ]);

            if ($chord->chord_content_id) {
                $chord->chordContent()->update([
                    'content' => $content,
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
