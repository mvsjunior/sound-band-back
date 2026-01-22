<?php

namespace App\Domains\Musical\Mappers;

use App\Domains\Musical\DTO\ChordDTO;
use App\Domains\Musical\Mappers\ToneDTOMapper;

class ChordDTOMapper
{
    public static function fromArray(array $data): ChordDTO
    {
        return new ChordDTO(
            $data['id'] ?? null,
            $data['version'] ?? null,
            ToneDTOMapper::fromArray($data),
            $data['music_id'] ?? $data['musicId'] ?? null,
            $data['content'] ?? $data['chord_content'] ?? null
        );
    }
}
