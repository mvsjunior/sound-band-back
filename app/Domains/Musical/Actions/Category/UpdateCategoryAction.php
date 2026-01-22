<?php

namespace App\Domains\Musical\Actions\Category;

use App\Domains\Musical\Database\CategoryRepository;
use App\Domains\Musical\Entities\Category;

class UpdateCategoryAction
{
    public function __construct(private CategoryRepository $categories)
    {
    }

    public function execute(int $id, string $name): void
    {
        $this->categories->update(new Category($id, $name));
    }
}
