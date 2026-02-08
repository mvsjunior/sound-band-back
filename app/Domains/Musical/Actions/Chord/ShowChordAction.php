<?php

namespace App\Domains\Musical\Actions\Chord;

use App\Domains\Musical\Database\Models\Chord;
use App\Domains\Musical\Mappers\ChordDTOMapper;

class ShowChordAction
{
    public function execute(int $id)
    {
        $chord = Chord::query()
            ->select(['id', 'music_id', 'tone', 'version', 'chord_content_id'])
            ->with(['chordContent:id,content'])
            ->find($id);

        if (!$chord) {
            return null;
        }

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
