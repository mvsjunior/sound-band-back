<?php

namespace App\Domains\Musical\Actions\Music;

use App\Domains\Musical\Database\MusicRepository;

class DeleteMusicAction
{
    public function __construct(private MusicRepository $musics)
    {
    }

    public function execute(int $id): void
    {
        $this->musics->delete($id);
    }
}
