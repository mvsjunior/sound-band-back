<?php

namespace App\Domains\Musical\DTO;

use App\Domains\Commons\DTO\DTO;

class ChordDTO extends DTO
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $version,
        public readonly ToneDTO $tone,
        public readonly int $musicId,
        public readonly ?string $content = null
    ){}
}
