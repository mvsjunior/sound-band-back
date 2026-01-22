<?php

namespace App\Domains\Musical\Actions\Lyrics;

use App\Domains\Musical\Database\LyricsRepository;
use App\Domains\Musical\Entities\Lyrics;

class UpdateLyricsAction
{
    public function __construct(private LyricsRepository $lyrics)
    {
    }

    public function execute(int $id, string $content): void
    {
        $this->lyrics->update(new Lyrics($id, $content));
    }
}
