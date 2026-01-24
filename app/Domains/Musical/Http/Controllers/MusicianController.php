<?php

namespace App\Domains\Musical\Http\Controllers;

use App\Domains\Commons\Http\ApiResponseTrait;
use App\Domains\Commons\Http\ResolvesPagination;
use App\Domains\Musical\Actions\Musician\DeleteMusicianAction;
use App\Domains\Musical\Actions\Musician\ListMusiciansAction;
use App\Domains\Musical\Actions\Musician\ShowMusicianAction;
use App\Domains\Musical\Actions\Musician\StoreMusicianAction;
use App\Domains\Musical\Actions\Musician\UpdateMusicianAction;
use App\Domains\Musical\Http\Requests\MusicianDeleteRequest;
use App\Domains\Musical\Http\Requests\MusicianShowRequest;
use App\Domains\Musical\Http\Requests\MusicianStoreRequest;
use App\Domains\Musical\Http\Requests\MusicianUpdateRequest;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class MusicianController
{
    use ApiResponseTrait;
    use ResolvesPagination;

    public function index(Request $request, ListMusiciansAction $action)
    {
        $statusId = filter_var($request->get('statusId'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        $id = filter_var($request->get('id'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        [$page, $perPage] = $this->resolvePagination($request);

        return $this->successResponse(
            $action->execute(
                $request->get('name'),
                $request->get('email'),
                $request->get('phone'),
                $statusId,
                $id,
                $page,
                $perPage
            )
        );
    }

    public function store(MusicianStoreRequest $request, StoreMusicianAction $action)
    {
        try {
            $musicianDTO = $action->execute(
                $request->validated('name'),
                $request->validated('email'),
                $request->validated('phone'),
                $request->validated('statusId')
            );

            return $this->successResponse($musicianDTO->toArray());
        } catch (Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function show(MusicianShowRequest $request, ShowMusicianAction $action)
    {
        $musicianDTO = $action->execute($request->validated('id'));

        if (!$musicianDTO) {
            return $this->notFoundResponse('Musician not found');
        }

        return $this->successResponse($musicianDTO->toArray());
    }

    public function update(MusicianUpdateRequest $request, UpdateMusicianAction $action)
    {
        try {
            $updated = $action->execute(
                $request->validated('id'),
                $request->validated('name'),
                $request->validated('email'),
                $request->validated('phone'),
                $request->validated('statusId')
            );

            if (!$updated) {
                return $this->notFoundResponse('Musician not found');
            }

            return $this->successResponse();
        } catch (Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function delete(MusicianDeleteRequest $request, DeleteMusicianAction $action)
    {
        try {
            $action->execute($request->validated('id'));
            return $this->successResponse();
        } catch (Throwable $th) {
            return $this->validationErrorResponse($th->getMessage());
        }
    }
}
