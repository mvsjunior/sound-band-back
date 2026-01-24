<?php


namespace App\Domains\Musical\Actions\Playlist;

use App\Domains\Musical\Database\Models\Playlist;
use App\Domains\Musical\DTO\PlaylistDTO;
use Illuminate\Support\Facades\DB;

class PlaylistStoreAction 
{
    public function execute(PlaylistDTO $dto): PlaylistDTO
    {
        
        $playlist = new Playlist();
        $playlist->name = $dto->name;
        $playlist->user_id = $dto->userId;

        $playlist->save();

        $musics = [];

        foreach ($dto->musics as $music) {
            $musicId = $music['id'] ?? null;
            $position = $music['position'] ?? null;

            if ($musicId === null) {
                continue;
            }

            $musics[$musicId] = ['position' => (int) $position];
        }

        if ($musics) {
            $playlist->musics()->sync($musics);
        }

        $playlist->loadMissing(['musics:id,name', 'user:id,name']);
        $musicItems = $playlist->musics->map(fn ($music) => [
            'id' => $music->id,
            'name' => $music->name,
            'position' => $music->pivot?->position,
        ])->all();

        return new PlaylistDTO(
            $playlist->id,
            $playlist->name,
            $playlist->user_id,
            $playlist->user?->name ?? '',
            $musicItems
        );
    }
}
