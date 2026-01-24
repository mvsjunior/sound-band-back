<?php

namespace App\Domains\Musical\Actions\Category;

use App\Domains\Commons\Exceptions\DuplicateEntryException;
use App\Domains\Musical\Database\Models\Category;
use App\Domains\Musical\DTO\CategoryDTO;
use Illuminate\Database\QueryException;

class StoreCategoryAction
{
    public function execute(string $name): CategoryDTO
    {
        try {
            $category = Category::create(['name' => $name]);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1] ?? null;
            if ($errorCode === 1062) {
                throw new DuplicateEntryException("The category name '{$name}' is already in use.");
            }

            throw $e;
        }

        return new CategoryDTO($category->id, $category->name);
    }
}
