<?php

namespace App\Domains\Musical\Mappers;

use App\Domains\Musical\DTO\MusicianSkillDTO;

class MusicianSkillDTOMapper
{
    public static function fromArray(array $data): MusicianSkillDTO
    {
        return new MusicianSkillDTO(
            $data['musician_id'] ?? 0,
            $data['musician_name'] ?? '',
            $data['skill_id'] ?? 0,
            $data['skill_name'] ?? ''
        );
    }
}
