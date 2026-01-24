<?php

namespace App\Domains\Musical\Actions\MusicianSkill;

use App\Domains\Musical\Database\Models\MusicianSkill;
use App\Domains\Musical\DTO\MusicianSkillDTO;

class ShowMusicianSkillAction
{
    public function execute(int $musicianId, int $skillId): ?MusicianSkillDTO
    {
        $musicianSkill = MusicianSkill::query()
            ->select(['musician_id', 'skill_id'])
            ->with([
                'musician:id,name',
                'skill:id,name',
            ])
            ->where('musician_id', '=', $musicianId)
            ->where('skill_id', '=', $skillId)
            ->first();

        if (!$musicianSkill) {
            return null;
        }

        return new MusicianSkillDTO(
            $musicianSkill->musician_id,
            $musicianSkill->musician?->name ?? '',
            $musicianSkill->skill_id,
            $musicianSkill->skill?->name ?? ''
        );
    }
}
