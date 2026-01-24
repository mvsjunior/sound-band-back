<?php

namespace App\Domains\Musical\Actions\MusicianSkill;

use App\Domains\Musical\Database\Models\MusicianSkill;
use App\Domains\Musical\Mappers\MusicianSkillDTOMapper;

class ListMusicianSkillsAction
{
    public function execute(
        ?int $musicianId,
        ?int $skillId,
        int $page,
        int $perPage
    ): array {
        $query = MusicianSkill::query()
            ->select(['musician_id', 'skill_id'])
            ->with([
                'musician:id,name',
                'skill:id,name',
            ])
            ->when($musicianId, function ($query) use ($musicianId) {
                return $query->where('musician_id', '=', $musicianId);
            })
            ->when($skillId, function ($query) use ($skillId) {
                return $query->where('skill_id', '=', $skillId);
            })
            ->orderBy('musician_id')
            ->orderBy('skill_id');

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);
        $items = $paginated->getCollection()->map(function (MusicianSkill $musicianSkill) {
            return [
                'musician_id' => $musicianSkill->musician_id,
                'musician_name' => $musicianSkill->musician?->name ?? '',
                'skill_id' => $musicianSkill->skill_id,
                'skill_name' => $musicianSkill->skill?->name ?? '',
            ];
        })->all();

        return [
            'items' => array_map(
                fn (array $musicianSkill) => MusicianSkillDTOMapper::fromArray($musicianSkill),
                $items
            ),
            'pagination' => [
                'page' => $paginated->currentPage(),
                'perPage' => $paginated->perPage(),
                'total' => $paginated->total(),
                'lastPage' => $paginated->lastPage(),
            ],
        ];
    }
}
