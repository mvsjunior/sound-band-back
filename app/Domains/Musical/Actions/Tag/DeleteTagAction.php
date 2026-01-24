<?php

namespace App\Domains\Musical\Actions\Tag;

use App\Domains\Musical\Database\Models\Tag;

class DeleteTagAction
{
    public function execute(int $id): void
    {
        Tag::query()->whereKey($id)->delete();
    }
}
