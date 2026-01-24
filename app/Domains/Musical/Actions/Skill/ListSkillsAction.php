<?php

namespace App\Domains\Musical\Actions\Skill;

use App\Domains\Musical\Database\Models\Skill;
use App\Domains\Musical\Mappers\SkillDTOMapper;

class ListSkillsAction
{
    public function execute(?string $name, ?int $id, int $page, int $perPage): array
    {
        $query = Skill::query()
            ->select(['id', 'name'])
            ->when($name !== null && $name !== '', function ($query) use ($name) {
                return $query->where('name', 'LIKE', "%{$name}%");
            })
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '=', $id);
            })
            ->orderBy('id');

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);
        $items = $paginated->getCollection()->map(fn (Skill $skill) => $skill->toArray())->all();

        return [
            'items' => array_map(
                fn (array $skill) => SkillDTOMapper::fromArray($skill),
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
