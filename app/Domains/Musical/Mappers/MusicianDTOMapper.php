<?php

namespace App\Domains\Musical\Mappers;

use App\Domains\Musical\DTO\MusicianDTO;
use App\Domains\Musical\DTO\MusicianStatusDTO;

class MusicianDTOMapper
{
    public static function fromArray(array $data): MusicianDTO
    {
        return new MusicianDTO(
            $data['id'] ?? null,
            $data['name'] ?? '',
            $data['email'] ?? null,
            $data['phone'] ?? null,
            new MusicianStatusDTO(
                $data['status_id'] ?? null,
                $data['status_name'] ?? ''
            ),
            $data['skills'] ?? []
        );
    }
}
