<?php

namespace App\Domains\Musical\Actions\Music;

use App\Domains\Musical\Database\Models\Music;
use App\Domains\Musical\Mappers\MusicDTOMapper;

class ListMusicsAction
{
    public function execute(
        ?string $name,
        ?string $artist,
        ?int $categoryId,
        ?int $id,
        int $page,
        int $perPage
    ): array
    {
        $query = Music::query()
            ->select(['id', 'name', 'artist', 'lyrics_id', 'category_id'])
            ->with([
                'category:id,name',
                'lyrics:id,content',
                'tags:id,name',
                'chords:id,music_id,tone,version',
            ])
            ->when($name !== null && $name !== '', function ($query) use ($name) {
                return $query->where('name', 'LIKE', "%{$name}%");
            })
            ->when($artist !== null && $artist !== '', function ($query) use ($artist) {
                return $query->where('artist', 'LIKE', "%{$artist}%");
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                return $query->where('category_id', '=', $categoryId);
            })
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '=', $id);
            })
            ->orderBy('id')
        ;

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);
        $items = $paginated->getCollection()->map(function (Music $music) {
            return [
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
                    'tone' => $chord->tone,
                ])->all(),
            ];
        })->all();

        return [
            'items' => array_map(
                fn (array $music) => MusicDTOMapper::fromArray($music),
                $items
            ),
            'pagination' => [
                'page' => $paginated->currentPage(),
                'perPage' => $paginated->perPage(),
                'total' => $paginated->total(),
                'lastPage' => $paginated->lastPage(),
            ],
        ];
    }
}
