<?php

namespace App\Domains\Musical\Actions\Music;

use App\Domains\Commons\Database\QueryClausule;
use App\Domains\Commons\Database\QueryExpression;
use App\Domains\Musical\Database\CategoryRepository;
use App\Domains\Musical\Database\LyricsRepository;
use App\Domains\Musical\Database\MusicRepository;
use App\Domains\Musical\Entities\Lyrics;
use App\Domains\Musical\Entities\Music;
use App\Domains\Musical\Mappers\MusicDTOMapper;

class StoreMusicAction
{
    public function __construct(
        private MusicRepository $musics,
        private LyricsRepository $lyrics,
        private CategoryRepository $categories
    ) {
    }

    public function execute(
        string $name,
        string $artist,
        string $lyricsContent,
        int $categoryId,
        array $tagIds = []
    ) {
        $lyrics = $this->lyrics->store(new Lyrics(null, $lyricsContent));
        $category = $this->categories->findById($categoryId);

        $music = new Music(
            null,
            $name,
            $artist,
            $lyrics,
            $category
        );

        $music = $this->musics->store($music);

        if (!empty($tagIds)) {
            $this->musics->syncTags($music->id(), $tagIds);
        }

        $expression = (new QueryExpression())
            ->add(new QueryClausule('musics.id', '=', $music->id()));

        $musicData = $this->musics->all($expression);

        return !empty($musicData) ? MusicDTOMapper::fromArray($musicData[0]) : null;
    }
}
