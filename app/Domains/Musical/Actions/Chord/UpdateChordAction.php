<?php

namespace App\Domains\Musical\Actions\Chord;

use App\Domains\Musical\Database\ChordRepository;
use App\Domains\Musical\Entities\Chord;
use App\Domains\Musical\Entities\ChordContent;

class UpdateChordAction
{
    public function __construct(private ChordRepository $chords)
    {
    }

    public function execute(int $id, int $musicId, int $toneId, string $content, string $version): void
    {
        $chord = new Chord(
            $id,
            $musicId,
            $toneId,
            null,
            $version
        );
        $contentEntity = new ChordContent(null, $content);

        $this->chords->update($chord, $contentEntity);
    }
}
