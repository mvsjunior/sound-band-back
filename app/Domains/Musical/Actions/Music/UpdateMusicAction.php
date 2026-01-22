<?php

namespace App\Domains\Musical\Actions\Music;

use App\Domains\Musical\Database\CategoryRepository;
use App\Domains\Musical\Database\LyricsRepository;
use App\Domains\Musical\Database\MusicRepository;
use App\Domains\Musical\Entities\Lyrics;
use App\Domains\Musical\Entities\Music;

class UpdateMusicAction
{
    public function __construct(
        private MusicRepository $musics,
        private LyricsRepository $lyrics,
        private CategoryRepository $categories
    ) {
    }

    public function execute(
        int $id,
        string $name,
        string $artist,
        string $lyricsContent,
        int $categoryId,
        bool $syncTags,
        array $tagIds = []
    ): bool {
        $existingMusic = $this->musics->findById($id);

        if (!$existingMusic) {
            return false;
        }

        $lyricsId = $existingMusic->lyrics()->id();
        if ($lyricsId) {
            $lyrics = new Lyrics($lyricsId, $lyricsContent);
            $this->lyrics->update($lyrics);
        } else {
            $lyrics = $this->lyrics->store(new Lyrics(null, $lyricsContent));
        }

        $category = $this->categories->findById($categoryId);

        $music = new Music(
            $id,
            $name,
            $artist,
            $lyrics,
            $category
        );
        $this->musics->update($music);

        if ($syncTags) {
            $this->musics->syncTags($id, $tagIds);
        }

        return true;
    }
}
