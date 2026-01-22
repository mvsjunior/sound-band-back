<?php

namespace App\Domains\Musical\Http\Controllers;

use App\Domains\Musical\Actions\Chord\DeleteChordAction;
use App\Domains\Musical\Actions\Chord\ListChordsAction;
use App\Domains\Musical\Actions\Chord\ShowChordAction;
use App\Domains\Musical\Actions\Chord\StoreChordAction;
use App\Domains\Musical\Actions\Chord\UpdateChordAction;
use App\Domains\Commons\Http\ApiResponseTrait;
use App\Domains\Musical\Http\Requests\ChordDeleteRequest;
use App\Domains\Musical\Http\Requests\ChordShowRequest;
use App\Domains\Musical\Http\Requests\ChordStoreRequest;
use App\Domains\Musical\Http\Requests\ChordUpdateRequest;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class ChordController
{
    use ApiResponseTrait;

    public function index(Request $request, ListChordsAction $action)
    {
        $id = filter_var($request->get('id'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        $musicId = filter_var($request->get('musicId'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        $toneId = filter_var($request->get('toneId'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

        return $this->successResponse(
            $action->execute($id, $musicId, $toneId)
        );
    }

    public function show(ChordShowRequest $request, ShowChordAction $action)
    {
        $chordDTO = $action->execute($request->validated('id'));

        if (!$chordDTO) {
            return $this->notFoundResponse('Chord not found');
        }

        return $this->successResponse($chordDTO);
    }

    public function store(ChordStoreRequest $request, StoreChordAction $action)
    {
        try {
            $chordDTO = $action->execute(
                $request->validated('musicId'),
                $request->validated('toneId'),
                $request->validated('content'),
                $request->validated('version')
            );

            return $this->successResponse($chordDTO);
        } catch (Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function update(ChordUpdateRequest $request, UpdateChordAction $action)
    {
        try {
            $action->execute(
                $request->validated('id'),
                $request->validated('musicId'),
                $request->validated('toneId'),
                $request->validated('content'),
                $request->validated('version')
            );

            return $this->successResponse();
        } catch (Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function delete(ChordDeleteRequest $request, DeleteChordAction $action)
    {
        try {
            $action->execute($request->validated('id'));
            return $this->successResponse();
        } catch (Throwable $th) {
            return $this->validationErrorResponse($th->getMessage());
        }
    }
}
