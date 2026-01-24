<?php

namespace App\Domains\Musical\Actions\Lyrics;

use App\Domains\Musical\Database\Models\Lyrics;

class DeleteLyricsAction
{
    public function execute(int $id): void
    {
        Lyrics::query()->whereKey($id)->delete();
    }
}
