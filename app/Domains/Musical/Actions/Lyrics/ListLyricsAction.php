<?php

namespace App\Domains\Musical\Actions\Lyrics;

use App\Domains\Commons\Database\QueryClausule;
use App\Domains\Commons\Database\QueryExpression;
use App\Domains\Musical\Database\LyricsRepository;

class ListLyricsAction
{
    public function __construct(private LyricsRepository $lyrics)
    {
    }

    public function execute(?string $content, ?int $id): array
    {
        $expression = new QueryExpression();

        if ($content !== null && $content !== '') {
            $expression = $expression->add(new QueryClausule('content', 'LIKE', "%{$content}%"));
        }

        if ($id) {
            $expression = $expression->add(new QueryClausule('id', '=', $id));
        }

        return $this->lyrics->all($expression);
    }
}
