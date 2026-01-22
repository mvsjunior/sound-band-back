<?php

namespace App\Domains\Musical\Actions\Category;

use App\Domains\Commons\Database\QueryClausule;
use App\Domains\Commons\Database\QueryExpression;
use App\Domains\Musical\Database\CategoryRepository;

class ListCategoriesAction
{
    public function __construct(private CategoryRepository $categories)
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

        return $this->categories->all($expression);
    }
}
