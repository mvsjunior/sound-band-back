<?php

namespace App\Domains\Musical\Actions\Music;

use App\Domains\Musical\Database\Models\Lyrics;
use App\Domains\Musical\Database\Models\Music;
use App\Domains\Musical\Mappers\MusicDTOMapper;
use Illuminate\Support\Facades\DB;

class StoreMusicAction
{
    public function execute(
        string $name,
        string $artist,
        string $lyricsContent,
        int $categoryId,
        array $tagIds = []
    ) {
        $music = DB::transaction(function () use ($name, $artist, $lyricsContent, $categoryId, $tagIds) {
            $lyrics = Lyrics::create(['content' => $lyricsContent]);

            $music = Music::create([
                'name' => $name,
                'artist' => $artist,
                'lyrics_id' => $lyrics->id,
                'category_id' => $categoryId,
            ]);

            if (!empty($tagIds)) {
                $music->tags()->sync($tagIds);
            }

            return $music;
        });

        $music->load([
            'category:id,name',
            'lyrics:id,content',
            'tags:id,name',
            'chords:id,music_id,tone,version',
        ]);

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
