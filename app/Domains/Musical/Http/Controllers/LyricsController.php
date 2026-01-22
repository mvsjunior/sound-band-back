<?php

namespace App\Domains\Musical\Http\Controllers;

use App\Domains\Musical\Actions\Lyrics\DeleteLyricsAction;
use App\Domains\Musical\Actions\Lyrics\ListLyricsAction;
use App\Domains\Musical\Actions\Lyrics\StoreLyricsAction;
use App\Domains\Musical\Actions\Lyrics\UpdateLyricsAction;
use App\Domains\Commons\Exceptions\DuplicateEntryException;
use App\Domains\Commons\Http\ApiResponseTrait;
use App\Domains\Musical\Http\Requests\LyricsDeleteRequest;
use App\Domains\Musical\Http\Requests\LyricsStoreRequest;
use App\Domains\Musical\Http\Requests\LyricsUpdateRequest;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class LyricsController
{
    use ApiResponseTrait;
    
    public function index(Request $request, ListLyricsAction $action)
    {
        $id = filter_var($request->get('id'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

        return $this->successResponse(
            $action->execute($request->get('content'), $id)
        );
    }

    public function store(LyricsStoreRequest $request, StoreLyricsAction $action)
    {
        try
        {
            $lyricsDTO = $action->execute($request->validated('content'));
            return $this->successResponse($lyricsDTO->toArray());
        }
        catch(DuplicateEntryException $e)
        {
            return $this->validationErrorResponse(['tag.name.duplicate.entry' => $e->getMessage()]);
        }
    }

    public function delete(LyricsDeleteRequest $request, DeleteLyricsAction $action)
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

    public function update(LyricsUpdateRequest $request, UpdateLyricsAction $action){
        try {
            $action->execute($request->validated('id'), $request->validated('content'));
            return $this->successResponse();
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse($th->getMessage());
        }
    }
}
