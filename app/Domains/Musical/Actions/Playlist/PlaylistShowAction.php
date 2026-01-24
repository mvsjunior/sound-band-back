<?php

namespace App\Domains\Musical\Actions\Playlist;

use App\Domains\Musical\Database\Models\Playlist;
use App\Domains\Musical\DTO\PlaylistDTO;

class PlaylistShowAction
{
    public function execute(int $id): ?PlaylistDTO
    {
        $playlist = Playlist::query()
            ->select(['id', 'name', 'user_id'])
            ->with([
                'user:id,name',
                'musics:id,name',
            ])
            ->find($id);

        if (!$playlist) {
            return null;
        }

        $musics = $playlist->musics->map(fn ($music) => [
            'id' => $music->id,
            'name' => $music->name,
            'position' => $music->pivot?->position,
        ])->all();

        return new PlaylistDTO(
            $playlist->id,
            $playlist->name,
            $playlist->user_id,
            $playlist->user?->name ?? '',
            $musics
        );
    }
}
