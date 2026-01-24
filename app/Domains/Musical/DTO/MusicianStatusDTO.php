<?php

namespace App\Domains\Musical\DTO;

use App\Domains\Commons\DTO\DTO;

class MusicianStatusDTO extends DTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name
    ){}
}
