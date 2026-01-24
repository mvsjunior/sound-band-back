<?php


namespace App\Domains\Musical\Actions\Playlist;

use App\Domains\Commons\DTO\PaginationMetaDTO;
use App\Domains\Musical\Database\Models\Playlist;
use App\Domains\Musical\DTO\PlaylistListDTO;

class PlaylistListAction 
{
    public function execute(?int $userId, ?string $playlistName, int $page, int $perPage): array
    {
        $modelPlaylist = Playlist::query()
            ->select(['id', 'name', 'user_id'])
            ->with(['user:id,name']);

        $modelPlaylist = $userId ? $modelPlaylist->where('user_id', '=', $userId) : $modelPlaylist;
        $modelPlaylist = $playlistName ? $modelPlaylist->where('name', 'LIKE', "%{$playlistName}%") : $modelPlaylist;

        $resultPaginated = $modelPlaylist->orderBy('id')
            ->paginate($perPage, ['*'], 'page', $page);
        $playlistDTO = array_map(
            fn ($playlist) => new PlaylistListDTO(
                $playlist->id,
                $playlist->name,
                $playlist->user?->id ?? 0,
                $playlist->user?->name ?? ''
            ),
            $resultPaginated->items()
        );

        $pagination =  PaginationMetaDTO::createFromLaravelPaginator($resultPaginated);
        
        return [
            "pagination" => $pagination,
            "items" => $playlistDTO
        ];
    }
}
