<?php

namespace App\Domains\Musical\Actions\Tag;

use App\Domains\Commons\Database\QueryClausule;
use App\Domains\Commons\Database\QueryExpression;
use App\Domains\Musical\Database\TagRepository;

class ListTagsAction
{
    public function __construct(private TagRepository $tags)
    {
    }

    public function execute(?string $name, ?int $id): array
    {
        $expression = new QueryExpression();

        if ($name !== null && $name !== '') {
            $expression = $expression->add(new QueryClausule('name', 'LIKE', "%{$name}%"));
        }

        if ($id) {
            $expression = $expression->add(new QueryClausule('id', '=', $id));
        }

        return $this->tags->all($expression);
    }
}
