<?php

namespace App\Domains\Musical\Actions\Skill;

use App\Domains\Musical\Database\Models\Skill;

class DeleteSkillAction
{
    public function execute(int $id): void
    {
        Skill::query()->whereKey($id)->delete();
    }
}
