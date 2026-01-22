<?php

namespace App\Domains\Musical\Actions\Tag;

use App\Domains\Musical\Database\TagRepository;
use App\Domains\Musical\Entities\Tag;

class UpdateTagAction
{
    public function __construct(private TagRepository $tags)
    {
    }

    public function execute(int $id, string $name): void
    {
        $this->tags->update(new Tag($id, $name));
    }
}
