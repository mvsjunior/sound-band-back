<?php

namespace App\Domains\Musical\Actions\Musician;

use App\Domains\Musical\Database\Models\Musician;
use App\Domains\Musical\Mappers\MusicianDTOMapper;

class ListMusiciansAction
{
    public function execute(
        ?string $name,
        ?string $email,
        ?string $phone,
        ?int $statusId,
        ?int $id,
        int $page,
        int $perPage
    ): array {
        $query = Musician::query()
            ->select(['id', 'name', 'email', 'phone', 'status_id'])
            ->with(['status:id,name', 'skills:id,name'])
            ->when($name !== null && $name !== '', function ($query) use ($name) {
                return $query->where('name', 'LIKE', "%{$name}%");
            })
            ->when($email !== null && $email !== '', function ($query) use ($email) {
                return $query->where('email', 'LIKE', "%{$email}%");
            })
            ->when($phone !== null && $phone !== '', function ($query) use ($phone) {
                return $query->where('phone', 'LIKE', "%{$phone}%");
            })
            ->when($statusId, function ($query) use ($statusId) {
                return $query->where('status_id', '=', $statusId);
            })
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '=', $id);
            })
            ->orderBy('id');

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);
        $items = $paginated->getCollection()->map(function (Musician $musician) {
            return [
                'id' => $musician->id,
                'name' => $musician->name,
                'email' => $musician->email,
                'phone' => $musician->phone,
                'status_id' => $musician->status_id,
                'status_name' => $musician->status?->name ?? '',
                'skills' => $musician->skills->map(fn ($skill) => [
                    'id' => $skill->id,
                    'name' => $skill->name,
                ])->all(),
            ];
        })->all();

        return [
            'items' => array_map(
                fn (array $musician) => MusicianDTOMapper::fromArray($musician),
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
