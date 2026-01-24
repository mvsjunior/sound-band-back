<?php

namespace App\Domains\Musical\Actions\Musician;

use App\Domains\Musical\Database\Models\Musician;

class DeleteMusicianAction
{
    public function execute(int $id): void
    {
        Musician::query()->whereKey($id)->delete();
    }
}
