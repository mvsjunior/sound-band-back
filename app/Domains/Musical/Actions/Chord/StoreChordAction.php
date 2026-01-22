<?php

namespace App\Domains\Musical\Actions\Chord;

use App\Domains\Musical\Database\ChordRepository;
use App\Domains\Musical\Entities\Chord;
use App\Domains\Musical\Entities\ChordContent;
use App\Domains\Musical\Mappers\ChordDTOMapper;

class StoreChordAction
{
    public function __construct(private ChordRepository $chords)
    {
    }

    public function execute(int $musicId, int $toneId, string $content, string $version)
    {
        $chord = new Chord(
            null,
            $musicId,
            $toneId,
            null,
            $version
        );
        $contentEntity = new ChordContent(null, $content);

        $chordData = $this->chords->store($chord, $contentEntity);

        return ChordDTOMapper::fromArray($chordData);
    }
}
