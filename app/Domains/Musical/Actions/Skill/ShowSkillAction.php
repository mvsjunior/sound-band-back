<?php

namespace App\Domains\Musical\Actions\Skill;

use App\Domains\Musical\Database\Models\Skill;
use App\Domains\Musical\DTO\SkillDTO;

class ShowSkillAction
{
    public function execute(int $id): ?SkillDTO
    {
        $skill = Skill::query()
            ->select(['id', 'name'])
            ->find($id);

        if (!$skill) {
            return null;
        }

        return new SkillDTO($skill->id, $skill->name);
    }
}
