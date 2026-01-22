<?php

namespace App\Domains\Musical\Http\Controllers;

use App\Domains\Commons\Database\QueryClausule;
use App\Domains\Commons\Database\QueryExpression;
use App\Domains\Commons\Http\ApiResponseTrait;
use App\Domains\Musical\Database\ChordRepository;
use App\Domains\Musical\Entities\Chord;
use App\Domains\Musical\Entities\ChordContent;
use App\Domains\Musical\Http\Requests\ChordDeleteRequest;
use App\Domains\Musical\Http\Requests\ChordShowRequest;
use App\Domains\Musical\Http\Requests\ChordStoreRequest;
use App\Domains\Musical\Http\Requests\ChordUpdateRequest;
use App\Domains\Musical\Mappers\ChordDTOMapper;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class ChordController
{
    use ApiResponseTrait;

    public function index(Request $request, ChordRepository $chordRepo)
    {
        $expression = new QueryExpression();
        $expression = $request->get('id', null) ? $expression->add(new QueryClausule('chords.id', '=', $request->get('id'))) : $expression;
        $expression = $request->get('musicId', null) ? $expression->add(new QueryClausule('chords.music_id', '=', $request->get('musicId'))) : $expression;
        $expression = $request->get('toneId', null) ? $expression->add(new QueryClausule('chords.tone_id', '=', $request->get('toneId'))) : $expression;

        $chords = array_map(
            fn (array $chord) => ChordDTOMapper::fromArray($chord),
            $chordRepo->all($expression)
        );

        return $this->successResponse($chords);
    }

    public function show(ChordShowRequest $request, ChordRepository $chordRepo)
    {
        $chordData = $chordRepo->findById($request->validated('id'));

        if (!$chordData) {
            return $this->notFoundResponse('Chord not found');
        }

        $chordDTO = ChordDTOMapper::fromArray($chordData);

        return $this->successResponse($chordDTO);
    }

    public function store(ChordStoreRequest $request, ChordRepository $chordRepo)
    {
        try {
            $chord = new Chord(
                null,
                $request->validated('musicId'),
                $request->validated('toneId'),
                null,
                $request->validated('version')
            );
            $content = new ChordContent(null, $request->validated('content'));

            $chordData = $chordRepo->store($chord, $content);
            $chordDTO = ChordDTOMapper::fromArray($chordData);

            return $this->successResponse($chordDTO);
        } catch (Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function update(ChordUpdateRequest $request, ChordRepository $chordRepo)
    {
        try {
            $chord = new Chord(
                $request->validated('id'),
                $request->validated('musicId'),
                $request->validated('toneId'),
                null,
                $request->validated('version')
            );
            $content = new ChordContent(null, $request->validated('content'));

            $chordRepo->update($chord, $content);

            return $this->successResponse();
        } catch (Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function delete(ChordDeleteRequest $request, ChordRepository $chordRepo)
    {
        try {
            $chordRepo->delete($request->validated('id'));
            return $this->successResponse();
        } catch (Throwable $th) {
            return $this->validationErrorResponse($th->getMessage());
        }
    }
}
