<?php

namespace App\Domains\Musical\Actions\Tag;

use App\Domains\Musical\Database\TagRepository;

class DeleteTagAction
{
    public function __construct(private TagRepository $tags)
    {
    }

    public function execute(int $id): void
    {
        $this->tags->delete($id);
    }
}
