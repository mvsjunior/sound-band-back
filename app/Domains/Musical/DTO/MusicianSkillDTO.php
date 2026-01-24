<?php

namespace App\Domains\Musical\DTO;

use App\Domains\Commons\DTO\DTO;

class MusicianSkillDTO extends DTO
{
    public function __construct(
        public readonly int $musicianId,
        public readonly string $musicianName,
        public readonly int $skillId,
        public readonly string $skillName
    ){}
}
