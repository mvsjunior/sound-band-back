<?php

namespace App\Domains\Musical\Actions\Skill;

use App\Domains\Commons\Exceptions\DuplicateEntryException;
use App\Domains\Musical\Database\Models\Skill;
use App\Domains\Musical\DTO\SkillDTO;
use Illuminate\Database\QueryException;

class StoreSkillAction
{
    public function execute(string $name): SkillDTO
    {
        try {
            $skill = Skill::query()->create(['name' => $name]);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1] ?? null;
            if ($errorCode === 1062) {
                throw new DuplicateEntryException("The skill name '{$name}' is already in use.");
            }

            throw $e;
        }

        return new SkillDTO($skill->id, $skill->name);
    }
}
