<?php

namespace App\Domains\Musical\Actions\Music;

use App\Domains\Musical\Database\Models\Lyrics;
use App\Domains\Musical\Database\Models\Music;
use Illuminate\Support\Facades\DB;

class UpdateMusicAction
{
    public function execute(
        int $id,
        string $name,
        string $artist,
        string $lyricsContent,
        int $categoryId,
        bool $syncTags,
        array $tagIds = []
    ): bool {
        $updated = false;

        DB::transaction(function () use (
            $id,
            $name,
            $artist,
            $lyricsContent,
            $categoryId,
            $syncTags,
            $tagIds,
            &$updated
        ) {
            $music = Music::query()->select(['id', 'lyrics_id'])->find($id);
            if (!$music) {
                return;
            }

            if ($music->lyrics_id) {
                Lyrics::query()->whereKey($music->lyrics_id)->update([
                    'content' => $lyricsContent,
                ]);
            } else {
                $lyrics = Lyrics::create(['content' => $lyricsContent]);
                $music->lyrics_id = $lyrics->id;
            }

            $music->name = $name;
            $music->artist = $artist;
            $music->category_id = $categoryId;
            $music->save();

            if ($syncTags) {
                $music->tags()->sync($tagIds);
            }

            $updated = true;
        });

        return $updated;
    }
}
