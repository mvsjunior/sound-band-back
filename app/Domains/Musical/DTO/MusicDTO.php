<?php

namespace App\Domains\Musical\DTO;

use App\Domains\Commons\DTO\DTO;

class MusicDTO extends DTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $artist,
        public readonly CategoryDTO $category,
        public readonly LyricsDTO $lyrics,
        public readonly array $tags = [],
        public readonly array $chords = []
    ){}
}
