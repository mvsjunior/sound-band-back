<?php

namespace App\Domains\Musical\Actions\Category;

use App\Domains\Musical\Database\Models\Category;

class DeleteCategoryAction
{
    public function execute(int $id): void
    {
        Category::query()->whereKey($id)->delete();
    }
}
