<?php

namespace App\Domains\Musical\Actions\MusicianSkill;

use App\Domains\Commons\Exceptions\DuplicateEntryException;
use App\Domains\Musical\Database\Models\MusicianSkill;
use App\Domains\Musical\DTO\MusicianSkillDTO;
use Illuminate\Database\QueryException;

class StoreMusicianSkillAction
{
    public function execute(int $musicianId, int $skillId): MusicianSkillDTO
    {
        try {
            $musicianSkill = MusicianSkill::query()->create([
                'musician_id' => $musicianId,
                'skill_id' => $skillId,
            ]);
        } catch (QueryException $e) {
            $errorCode = $e->errorInfo[1] ?? null;
            if ($errorCode === 1062) {
                throw new DuplicateEntryException('The musician skill association already exists.');
            }

            throw $e;
        }

        $musicianSkill->loadMissing([
            'musician:id,name',
            'skill:id,name',
        ]);

        return new MusicianSkillDTO(
            $musicianSkill->musician_id,
            $musicianSkill->musician?->name ?? '',
            $musicianSkill->skill_id,
            $musicianSkill->skill?->name ?? ''
        );
    }
}
