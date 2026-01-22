<?php

namespace App\Domains\Musical\Actions\Chord;

use App\Domains\Musical\Database\ChordRepository;

class DeleteChordAction
{
    public function __construct(private ChordRepository $chords)
    {
    }

    public function execute(int $id): void
    {
        $this->chords->delete($id);
    }
}
