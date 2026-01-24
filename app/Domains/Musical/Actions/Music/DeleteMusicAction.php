<?php

namespace App\Domains\Musical\Actions\Music;

use App\Domains\Musical\Database\Models\Music;

class DeleteMusicAction
{
    public function execute(int $id): void
    {
        Music::query()->whereKey($id)->forceDelete();
    }
}
