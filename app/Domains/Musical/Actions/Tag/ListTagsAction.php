<?php

namespace App\Domains\Musical\Actions\Tag;

use App\Domains\Musical\Database\Models\Tag;

class ListTagsAction
{
    public function execute(?string $name, ?int $id, int $page, int $perPage): array
    {
        $query = Tag::query()
            ->select(['id', 'name'])
            ->when($name !== null && $name !== '', function ($query) use ($name) {
                return $query->where('name', 'LIKE', "%{$name}%");
            })
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '=', $id);
            })
            ->orderBy('id');

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);
        $items = $paginated->getCollection()->map(fn (Tag $tag) => $tag->toArray())->all();

        return [
            'items' => $items,
            'pagination' => [
                'page' => $paginated->currentPage(),
                'perPage' => $paginated->perPage(),
                'total' => $paginated->total(),
                'lastPage' => $paginated->lastPage(),
            ],
        ];
    }
}
