<?php

namespace App\Domains\Musical\Mappers;

use App\Domains\Musical\DTO\SkillDTO;
use stdClass;

class SkillDTOMapper
{
    public static function fromArray(array $data): SkillDTO
    {
        return new SkillDTO(
            $data['id'] ?? null,
            $data['name'] ?? ''
        );
    }

    public static function fromStdObj(stdClass $data): SkillDTO
    {
        return new SkillDTO(
            $data->id ?? null,
            $data->name ?? ''
        );
    }
}
