<?php

namespace App\Domains\Musical\Mappers;

use App\Domains\Musical\DTO\TagDTO;
use stdClass;

class TagDTOMapper 
{
    public static function fromArray(array $data): TagDTO
    {
        return new TagDTO(
            $data['id'] ?? null,
            $data['name'] ?? ""
        );
    }

    public static function fromStdObj(stdClass $data): TagDTO
    {
        return new TagDTO(
            $data->id ?? null,
            $data->name ?? ""
        );
    }
}