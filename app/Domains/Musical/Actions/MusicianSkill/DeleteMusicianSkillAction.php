<?php

namespace App\Domains\Musical\Actions\MusicianSkill;

use App\Domains\Musical\Database\Models\MusicianSkill;

class DeleteMusicianSkillAction
{
    public function execute(int $musicianId, int $skillId): void
    {
        MusicianSkill::query()
            ->where('musician_id', '=', $musicianId)
            ->where('skill_id', '=', $skillId)
            ->delete();
    }
}
