<?php

namespace App\Domains\Musical\Actions\Tag;

use App\Domains\Commons\Exceptions\DuplicateEntryException;
use App\Domains\Musical\Database\Models\Tag;
use App\Domains\Musical\DTO\TagDTO;
use Illuminate\Database\QueryException;

class StoreTagAction
{
    public function execute(string $name): TagDTO
    {
        try {
            $tag = Tag::create(['name' => $name]);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1] ?? null;
            if ($errorCode === 1062) {
                throw new DuplicateEntryException("The tag name '{$name}' is already in use.");
            }

            throw $e;
        }

        return new TagDTO($tag->id, $tag->name);
    }
}
