<?php

namespace App\Domains\Musical\Actions\Tag;

use App\Domains\Musical\Database\Models\Tag;
use App\Domains\Musical\DTO\TagDTO;

class ShowTagAction
{
    public function execute(int $id): ?TagDTO
    {
        $tag = Tag::query()
            ->select(['id', 'name'])
            ->find($id);

        if (!$tag) {
            return null;
        }

        return new TagDTO($tag->id, $tag->name);
    }
}
