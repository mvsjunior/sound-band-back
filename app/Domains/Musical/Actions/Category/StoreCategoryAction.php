<?php

namespace App\Domains\Musical\Actions\Category;

use App\Domains\Musical\Database\CategoryRepository;
use App\Domains\Musical\DTO\CategoryDTO;
use App\Domains\Musical\Entities\Category;

class StoreCategoryAction
{
    public function __construct(private CategoryRepository $categories)
    {
    }

    public function execute(string $name): CategoryDTO
    {
        $category = $this->categories->store(new Category(null, $name));

        return new CategoryDTO($category->id(), $category->name());
    }
}
