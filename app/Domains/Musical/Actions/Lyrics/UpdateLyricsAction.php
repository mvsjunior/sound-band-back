<?php

namespace App\Domains\Musical\Actions\Lyrics;

use App\Domains\Musical\Database\Models\Lyrics;

class UpdateLyricsAction
{
    public function execute(int $id, string $content): void
    {
        Lyrics::query()->whereKey($id)->update(['content' => $content]);
    }
}
