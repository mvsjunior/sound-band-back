<?php

namespace App\Domains\Musical\Actions\Lyrics;

use App\Domains\Musical\Database\Models\Lyrics;

class ListLyricsAction
{
    public function execute(?string $content, ?int $id, int $page, int $perPage): array
    {
        $query = Lyrics::query()
            ->select(['id', 'content'])
            ->when($content !== null && $content !== '', function ($query) use ($content) {
                return $query->where('content', 'LIKE', "%{$content}%");
            })
            ->when($id, function ($query) use ($id) {
                return $query->where('id', '=', $id);
            })
            ->orderBy('id');

        $paginated = $query->paginate($perPage, ['*'], 'page', $page);
        $items = $paginated->getCollection()->map(fn (Lyrics $lyrics) => $lyrics->toArray())->all();

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
