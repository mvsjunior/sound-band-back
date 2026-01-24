<?php

namespace App\Domains\Musical\Actions\Playlist;

use App\Domains\Musical\Database\Models\Playlist;

class PlaylistDeleteAction
{
    public function execute(int $id): void
    {
        Playlist::query()->whereKey($id)->delete();
    }
}
