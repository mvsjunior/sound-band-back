<?php

namespace App\Domains\Musical\Actions\Lyrics;

use App\Domains\Musical\Database\LyricsRepository;
use App\Domains\Musical\DTO\LyricsDTO;
use App\Domains\Musical\Entities\Lyrics;

class StoreLyricsAction
{
    public function __construct(private LyricsRepository $lyrics)
    {
    }

    public function execute(string $content): LyricsDTO
    {
        $lyrics = $this->lyrics->store(new Lyrics(null, $content));

        return new LyricsDTO($lyrics->id(), $lyrics->content());
    }
}
