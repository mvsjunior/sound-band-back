<?php

namespace App\Domains\Musical\Actions\Tag;

use App\Domains\Musical\Database\TagRepository;
use App\Domains\Musical\DTO\TagDTO;
use App\Domains\Musical\Entities\Tag;

class StoreTagAction
{
    public function __construct(private TagRepository $tags)
    {
    }

    public function execute(string $name): TagDTO
    {
        $tag = $this->tags->store(new Tag(null, $name));

        return new TagDTO($tag->id(), $tag->name());
    }
}
