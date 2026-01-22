<?php

namespace App\Domains\Musical\Mappers;

use App\Domains\Musical\DTO\ToneDTO;

class ToneDTOMapper
{
    public static function fromArray(array $data): ToneDTO
    {
        return new ToneDTO(
            $data['tone_id'] ?? $data['id'] ?? null,
            $data['tone_name'] ?? $data['name'] ?? '',
            $data['tone_type'] ?? $data['type'] ?? ''
        );
    }
}
