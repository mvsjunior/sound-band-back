<?php

namespace App\Domains\Musical\Actions\Category;

use App\Domains\Musical\Database\Models\Category;
use App\Domains\Musical\DTO\CategoryDTO;

class ShowCategoryAction
{
    public function execute(int $id): ?CategoryDTO
    {
        $category = Category::query()
            ->select(['id', 'name'])
            ->find($id);

        if (!$category) {
            return null;
        }

        return new CategoryDTO($category->id, $category->name);
    }
}
