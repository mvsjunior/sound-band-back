<?php

namespace App\Domains\Musical\Actions\Category;

use App\Domains\Commons\Exceptions\DuplicateEntryException;
use App\Domains\Musical\Database\Models\Category;
use Illuminate\Database\QueryException;

class UpdateCategoryAction
{
    public function execute(int $id, string $name): void
    {
        try {
            Category::query()->whereKey($id)->update(['name' => $name]);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1] ?? null;
            if ($errorCode === 1062) {
                throw new DuplicateEntryException("The category name '{$name}' is already in use.");
            }

            throw $e;
        }
    }
}
