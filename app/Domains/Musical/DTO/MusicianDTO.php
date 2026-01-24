<?php

namespace App\Domains\Musical\DTO;

use App\Domains\Commons\DTO\DTO;

class MusicianDTO extends DTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly ?string $email,
        public readonly ?string $phone,
        public readonly MusicianStatusDTO $status,
        public readonly array $skills = []
    ){}
}
