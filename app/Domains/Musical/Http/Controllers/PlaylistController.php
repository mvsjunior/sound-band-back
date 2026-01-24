<?php

namespace App\Domains\Musical\Http\Controllers;

use App\Domains\Commons\Http\ApiResponseTrait;
use App\Domains\Commons\Http\ResolvesPagination;
use App\Domains\Musical\Actions\Playlist\PlaylistDeleteAction;
use App\Domains\Musical\Actions\Playlist\PlaylistListAction;
use App\Domains\Musical\Actions\Playlist\PlaylistShowAction;
use App\Domains\Musical\Actions\Playlist\PlaylistStoreAction;
use App\Domains\Musical\Actions\Playlist\PlaylistUpdateAction;
use App\Domains\Musical\DTO\PlaylistDTO;
use App\Domains\Musical\Http\Requests\PlaylistDeleteRequest;
use App\Domains\Musical\Http\Requests\PlaylistShowRequest;
use App\Domains\Musical\Http\Requests\PlaylistStoreRequest;
use App\Domains\Musical\Http\Requests\PlaylistUpdateRequest;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class PlaylistController
{
    use ApiResponseTrait;
    use ResolvesPagination;
    
    public function index(Request $request, PlaylistListAction $action)
    {
        $playlistName = $request->get('playlistName');
        $userId = filter_var($request->get('userId'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        [$page, $perPage] = $this->resolvePagination($request);

        $playlistPaginated = $action->execute($userId, $playlistName, $page, $perPage);

        return $this->successResponse($playlistPaginated);
    }

    public function store(
        PlaylistStoreRequest $request,
        PlaylistStoreAction $action
    )
    {
        try
        {
            $userId = auth('api')->user()->id;
            $userName = auth('api')->user()->name;
            $playlist = $action->execute(new PlaylistDTO(
                null,
                $request->validated('name'),
                $userId,
                $userName,
                $request->validated('musics', [])
            ));

            return $this->successResponse($playlist);
        }
        catch(\Throwable $th)
        {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function show(PlaylistShowRequest $request, PlaylistShowAction $action)
    {
        $playlistDTO = $action->execute($request->validated('id'));

        if (!$playlistDTO) {
            return $this->notFoundResponse('Playlist not found');
        }

        return $this->successResponse($playlistDTO);
    }

    public function delete(PlaylistDeleteRequest $request, PlaylistDeleteAction $action)
    {
        try
        {
            $action->execute($request->validated('id'));
            return $this->successResponse();
        }
        catch(Throwable $th)
        {
            return $this->validationErrorResponse($th->getMessage());
        }
    }

    public function update(
        PlaylistUpdateRequest $request,
        PlaylistUpdateAction $action
    ){
        try {
            $updated = $action->execute(
                $request->validated('id'),
                $request->validated('name'),
                $request->has('musics') ? $request->validated('musics') : null
            );

            if (!$updated) {
                return $this->notFoundResponse('Playlist not found');
            }

            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
}
