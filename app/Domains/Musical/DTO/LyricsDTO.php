<?php

namespace App\Domains\Musical\DTO;

use App\Domains\Commons\DTO\DTO;

class LyricsDTO extends DTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $content
    ){}
}