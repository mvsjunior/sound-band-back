<?php

namespace App\Domains\Musical\Actions\Chord;

use App\Domains\Musical\Database\Models\Chord;
use App\Domains\Musical\Mappers\ChordDTOMapper;

class ListChordsAction
{
    public function execute(?int $id, ?int $musicId, ?string $tone, int $page, int $perPage): array
    {
        $query = Chord::query()
            ->select(['id', 'music_id', 'tone', 'version'])
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '=', $id);
            })
            ->when($musicId, function ($query) use ($musicId) {
                return $query->where('music_id', '=', $musicId);
            })
            ->when($tone !== null && $tone !== '', function ($query) use ($tone) {
                return $query->where('tone', '=', $tone);
            })
            ->orderBy('id');

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);
        $items = $paginated->getCollection()->map(function (Chord $chord) {
            return [
                'id' => $chord->id,
                'music_id' => $chord->music_id,
                'tone_id' => 0,
                'version' => $chord->version,
                'tone_name' => $chord->tone ?? '',
                'tone_type' => $chord->tone ?? '',
            ];
        })->all();

        return [
            'items' => array_map(
                fn (array $chord) => ChordDTOMapper::fromArray($chord),
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
