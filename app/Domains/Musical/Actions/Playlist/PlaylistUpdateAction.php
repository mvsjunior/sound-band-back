<?php

namespace App\Domains\Musical\Actions\Playlist;

use App\Domains\Musical\Database\Models\Playlist;

class PlaylistUpdateAction
{
    public function execute(int $id, string $name, ?array $musics): bool
    {
        $playlist = Playlist::query()->find($id);

        if (!$playlist) {
            return false;
        }

        $playlist->update([
            'name' => $name,
        ]);

        if ($musics !== null) {
            $syncData = [];
            foreach ($musics as $music) {
                $musicId = $music['id'] ?? null;
                $position = $music['position'] ?? null;

                if ($musicId === null) {
                    continue;
                }

                $syncData[$musicId] = ['position' => (int) $position];
            }

            $playlist->musics()->sync($syncData);
        }

        return true;
    }
}
