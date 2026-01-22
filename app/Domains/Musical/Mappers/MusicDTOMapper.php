<?php

namespace App\Domains\Musical\Mappers;

use App\Domains\Musical\DTO\CategoryDTO;
use App\Domains\Musical\DTO\LyricsDTO;
use App\Domains\Musical\DTO\MusicDTO;
use App\Domains\Musical\Mappers\TagDTOMapper;

class MusicDTOMapper 
{
    public static function fromArray(array $data)
    {
        $tags = array_map(
            fn (array $tag) => TagDTOMapper::fromArray($tag),
            $data['tags'] ?? []
        );

        return new MusicDTO(
            $data['id'] ?? null,
            $data['name'] ?? "",
            $data['artist'] ?? '',
            new CategoryDTO(
                ($data['category_id'] ?? null), 
                ($data['category_name'] ?? '')
            ),
            new LyricsDTO($data['lyrics_id'] ?? null, $data['lyrics_content'] ?? ''),
            $tags
        );
    }
}
