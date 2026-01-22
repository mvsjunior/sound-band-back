<?php

namespace App\Domains\Musical\Mappers;

use App\Domains\Musical\DTO\CategoryDTO;
use App\Domains\Musical\DTO\LyricsDTO;
use App\Domains\Musical\DTO\MusicDTO;
use App\Domains\Musical\Mappers\TagDTOMapper;
use App\Domains\Musical\Mappers\ChordDTOMapper;

class MusicDTOMapper 
{
    public static function fromArray(array $data)
    {
        $tags = array_map(
            fn (array $tag) => TagDTOMapper::fromArray($tag),
            $data['tags'] ?? []
        );

        $chords = array_map(
            fn (array $chord) => ChordDTOMapper::fromArray($chord),
            $data['chords'] ?? []
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
            $tags,
            $chords
        );
    }
}
