<?php

namespace App\Domains\Musical\Http\Controllers;

use App\Domains\Musical\Actions\Music\DeleteMusicAction;
use App\Domains\Musical\Actions\Music\ListMusicsAction;
use App\Domains\Musical\Actions\Music\StoreMusicAction;
use App\Domains\Musical\Actions\Music\UpdateMusicAction;
use App\Domains\Commons\Http\ApiResponseTrait;
use App\Domains\Musical\Http\Requests\MusicDeleteRequest;
use App\Domains\Musical\Http\Requests\MusicStoreRequest;
use App\Domains\Musical\Http\Requests\MusicUpdateRequest;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class MusicController
{
    use ApiResponseTrait;
    
    public function index(Request $request, ListMusicsAction $action)
    {
        $categoryId = filter_var($request->get('categoryId'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        $id = filter_var($request->get('id'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

        return $this->successResponse(
            $action->execute(
                $request->get('name'),
                $request->get('artist'),
                $categoryId,
                $id
            )
        );
    }

    public function store(
        MusicStoreRequest $request,
        StoreMusicAction $action
    )
    {
        try
        {
            $musicDTO = $action->execute(
                $request->validated('name'),
                $request->validated('artist'),
                $request->validated('lyrics'),
                $request->validated('categoryId'),
                $request->validated('tagIds', [])
            );

            return $this->successResponse($musicDTO);
        }
        catch(\Throwable $th)
        {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function delete(MusicDeleteRequest $request, DeleteMusicAction $action)
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
        MusicUpdateRequest $request,
        UpdateMusicAction $action
    ){
        try {
            $updated = $action->execute(
                $request->validated('id'),
                $request->validated('name'),
                $request->validated('artist'),
                $request->validated('lyrics'),
                $request->validated('categoryId'),
                $request->has('tagIds'),
                $request->validated('tagIds', [])
            );

            if (!$updated) {
                return $this->notFoundResponse('Music not found');
            }

            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
}
