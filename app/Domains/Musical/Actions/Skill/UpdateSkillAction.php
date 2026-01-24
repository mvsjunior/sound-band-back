<?php

namespace App\Domains\Musical\Actions\Skill;

use App\Domains\Commons\Exceptions\DuplicateEntryException;
use App\Domains\Musical\Database\Models\Skill;
use Illuminate\Database\QueryException;

class UpdateSkillAction
{
    public function execute(int $id, string $name): bool
    {
        $skill = Skill::query()->find($id);

        if (!$skill) {
            return false;
        }

        try {
            $skill->update(['name' => $name]);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1] ?? null;
            if ($errorCode === 1062) {
                throw new DuplicateEntryException("The skill name '{$name}' is already in use.");
            }

            throw $e;
        }

        return true;
    }
}
