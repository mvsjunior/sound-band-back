<?php

namespace App\Domains\Musical\Actions\Lyrics;

use App\Domains\Musical\Database\Models\Lyrics;
use App\Domains\Musical\DTO\LyricsDTO;

class ShowLyricsAction
{
    public function execute(int $id): ?LyricsDTO
    {
        $lyrics = Lyrics::query()
            ->select(['id', 'content'])
            ->find($id);

        if (!$lyrics) {
            return null;
        }

        return new LyricsDTO($lyrics->id, $lyrics->content);
    }
}
