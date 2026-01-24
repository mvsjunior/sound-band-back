<?php

namespace App\Domains\Musical\Actions\MusicianSkill;

use App\Domains\Commons\Exceptions\DuplicateEntryException;
use App\Domains\Musical\Database\Models\MusicianSkill;
use Illuminate\Database\QueryException;

class UpdateMusicianSkillAction
{
    public function execute(
        int $musicianId,
        int $skillId,
        int $newMusicianId,
        int $newSkillId
    ): bool {
        $exists = MusicianSkill::query()
            ->where('musician_id', '=', $musicianId)
            ->where('skill_id', '=', $skillId)
            ->exists();

        if (!$exists) {
            return false;
        }

        try {
            MusicianSkill::query()
                ->where('musician_id', '=', $musicianId)
                ->where('skill_id', '=', $skillId)
                ->update([
                    'musician_id' => $newMusicianId,
                    'skill_id' => $newSkillId,
                ]);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1] ?? null;
            if ($errorCode === 1062) {
                throw new DuplicateEntryException('The musician skill association already exists.');
            }

            throw $e;
        }

        return true;
    }
}
