<?php

namespace App\Domains\Musical\Actions\Tag;

use App\Domains\Commons\Exceptions\DuplicateEntryException;
use App\Domains\Musical\Database\Models\Tag;
use Illuminate\Database\QueryException;

class UpdateTagAction
{
    public function execute(int $id, string $name): void
    {
        try {
            Tag::query()->whereKey($id)->update(['name' => $name]);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1] ?? null;
            if ($errorCode === 1062) {
                throw new DuplicateEntryException("The tag name '{$name}' is already in use.");
            }

            throw $e;
        }
    }
}
