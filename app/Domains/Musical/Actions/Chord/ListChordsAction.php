<?php

namespace App\Domains\Musical\Actions\Chord;

use App\Domains\Commons\Database\QueryClausule;
use App\Domains\Commons\Database\QueryExpression;
use App\Domains\Musical\Database\ChordRepository;
use App\Domains\Musical\Mappers\ChordDTOMapper;

class ListChordsAction
{
    public function __construct(private ChordRepository $chords)
    {
    }

    public function execute(?int $id, ?int $musicId, ?int $toneId): array
    {
        $expression = new QueryExpression();

        if ($id) {
            $expression = $expression->add(new QueryClausule('chords.id', '=', $id));
        }

        if ($musicId) {
            $expression = $expression->add(new QueryClausule('chords.music_id', '=', $musicId));
        }

        if ($toneId) {
            $expression = $expression->add(new QueryClausule('chords.tone_id', '=', $toneId));
        }

        return array_map(
            fn (array $chord) => ChordDTOMapper::fromArray($chord),
            $this->chords->all($expression)
        );
    }
}
