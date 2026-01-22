<?php

namespace App\Domains\Musical\Actions\Music;

use App\Domains\Commons\Database\QueryClausule;
use App\Domains\Commons\Database\QueryExpression;
use App\Domains\Musical\Database\MusicRepository;
use App\Domains\Musical\Mappers\MusicDTOMapper;

class ListMusicsAction
{
    public function __construct(private MusicRepository $musics)
    {
    }

    public function execute(?string $name, ?string $artist, ?int $categoryId, ?int $id): array
    {
        $expression = new QueryExpression();

        if ($name !== null && $name !== '') {
            $expression = $expression->add(new QueryClausule('musics.name', 'LIKE', "%{$name}%"));
        }

        if ($artist !== null && $artist !== '') {
            $expression = $expression->add(new QueryClausule('musics.artist', 'LIKE', "%{$artist}%"));
        }

        if ($categoryId) {
            $expression = $expression->add(new QueryClausule('musics.category_id', '=', $categoryId));
        }

        if ($id) {
            $expression = $expression->add(new QueryClausule('musics.id', '=', $id));
        }

        return array_map(
            fn (array $music) => MusicDTOMapper::fromArray($music),
            $this->musics->all($expression)
        );
    }
}
