<?php

namespace App\Domains\Musical\Actions\Lyrics;

use App\Domains\Musical\Database\LyricsRepository;

class DeleteLyricsAction
{
    public function __construct(private LyricsRepository $lyrics)
    {
    }

    public function execute(int $id): void
    {
        $this->lyrics->delete($id);
    }
}
