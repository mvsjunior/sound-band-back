<?php

namespace App\Domains\Musical\Actions\Lyrics;

use App\Domains\Musical\Database\Models\Lyrics;
use App\Domains\Musical\DTO\LyricsDTO;

class StoreLyricsAction
{
    public function execute(string $content): LyricsDTO
    {
        $lyrics = Lyrics::create(['content' => $content]);

        return new LyricsDTO($lyrics->id, $lyrics->content);
    }
}
