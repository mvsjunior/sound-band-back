<?php

namespace App\Domains\Musical\Mappers;

use App\Domains\Musical\DTO\CategoryDTO;
use App\Domains\Musical\Entities\Category;
use stdClass;

class CategoryDTOMapper 
{
    public static function fromArray(array $data): CategoryDTO
    {
        return new CategoryDTO(
            $data['id'] ?? null,
            $data['name'] ?? ""
        );
    }

    public static function fromStdObj(stdClass $data):CategoryDTO
    {
        return new CategoryDTO(
            $data->id ?? null,
            $data->name ?? ""
        );
    }
}