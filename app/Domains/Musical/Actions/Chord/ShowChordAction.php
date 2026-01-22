<?php

namespace App\Domains\Musical\Actions\Chord;

use App\Domains\Musical\Database\ChordRepository;
use App\Domains\Musical\Mappers\ChordDTOMapper;

class ShowChordAction
{
    public function __construct(private ChordRepository $chords)
    {
    }

    public function execute(int $id)
    {
        $chordData = $this->chords->findById($id);

        return $chordData ? ChordDTOMapper::fromArray($chordData) : null;
    }
}
