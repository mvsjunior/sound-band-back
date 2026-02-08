<?php

namespace App\Domains\Musical\Actions\Chord;

use App\Domains\Musical\Database\Models\Chord;
use App\Domains\Musical\Database\Models\ChordContent;
use App\Domains\Musical\Mappers\ChordDTOMapper;
use Illuminate\Support\Facades\DB;

class StoreChordAction
{
    public function execute(int $musicId, string $tone, string $content, string $version)
    {
        $chord = DB::transaction(function () use ($musicId, $tone, $content, $version) {
            $contentModel = ChordContent::create(['content' => $content]);

            return Chord::create([
                'music_id' => $musicId,
                'tone' => $tone,
                'chord_content_id' => $contentModel->id,
                'version' => $version,
            ]);
        });

        $chord->load(['chordContent:id,content']);

        $chordData = [
            'id' => $chord->id,
            'music_id' => $chord->music_id,
            'version' => $chord->version,
            'chord_content' => $chord->chordContent?->content,
            'tone' => $chord->tone ?? '',
        ];

        return ChordDTOMapper::fromArray($chordData);
    }
}
