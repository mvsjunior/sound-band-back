<?php

namespace App\Domains\Musical\Actions\Category;

use App\Domains\Musical\Database\CategoryRepository;

class DeleteCategoryAction
{
    public function __construct(private CategoryRepository $categories)
    {
    }

    public function execute(int $id): void
    {
        $this->categories->delete($id);
    }
}
