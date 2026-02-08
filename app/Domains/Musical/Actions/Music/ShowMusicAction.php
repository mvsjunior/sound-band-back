<?php

namespace App\Domains\Musical\Actions\Music;

use App\Domains\Musical\Database\Models\Music;
use App\Domains\Musical\DTO\MusicDTO;
use App\Domains\Musical\Mappers\MusicDTOMapper;

class ShowMusicAction
{
    public function execute(int $id): ?MusicDTO
    {
        $music = Music::query()
            ->select(['id', 'name', 'artist', 'lyrics_id', 'category_id'])
            ->with([
                'category:id,name',
                'lyrics:id,content',
                'tags:id,name',
                'chords:id,music_id,tone,version',
            ])
            ->find($id);

        if (!$music) {
            return null;
        }

        $musicData = [
            'id' => $music->id,
            'name' => $music->name,
            'artist' => $music->artist ?? '',
            'category_id' => $music->category?->id,
            'category_name' => $music->category?->name ?? '',
            'lyrics_id' => $music->lyrics?->id,
            'lyrics_content' => $music->lyrics?->content ?? '',
            'tags' => $music->tags->map(fn ($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
            ])->all(),
            'chords' => $music->chords->map(fn ($chord) => [
                'id' => $chord->id,
                'music_id' => $chord->music_id,
                'version' => $chord->version,
                'tone' => $chord->tone ?? '',
            ])->all(),
        ];

        return MusicDTOMapper::fromArray($musicData);
    }
}
