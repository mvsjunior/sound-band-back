<?php

namespace App\Domains\Musical\Http\Controllers;

use App\Domains\Commons\Database\QueryClausule;
use App\Domains\Commons\Database\QueryExpression;
use App\Domains\Commons\Exceptions\DuplicateEntryException;
use App\Domains\Commons\Http\ApiResponseTrait;
use App\Domains\Musical\Database\LyricsRepository;
use App\Domains\Musical\DTO\LyricsDTO;
use App\Domains\Musical\Entities\Lyrics;
use App\Domains\Musical\Http\Requests\LyricsDeleteRequest;
use App\Domains\Musical\Http\Requests\LyricsStoreRequest;
use App\Domains\Musical\Http\Requests\LyricsUpdateRequest;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class LyricsController
{
    use ApiResponseTrait;
    
    public function index(Request $request, LyricsRepository $lyricsRepo)
    {
        $expression = new QueryExpression();
        $expression = $request->get('content', null) ? $expression->add(new QueryClausule('content', 'LIKE', "%{$request->get('content','')}%")) : $expression;
        $expression = $request->get('id', null)   ? $expression->add(new QueryClausule("id", "=", $request->get('id')))                 : $expression;

        return $this->successResponse($lyricsRepo->all($expression));
    }

    public function store(LyricsStoreRequest $request, LyricsRepository $lyricsRepo)
    {
        try
        {
            $tag = $lyricsRepo->store(new Lyrics(null,$request->validated("content")));
            $tagDTO = new LyricsDTO($tag->id(), $tag->content());

            return $this->successResponse($tagDTO->toArray());
        }
        catch(DuplicateEntryException $e)
        {
            return $this->validationErrorResponse(['tag.name.duplicate.entry' => $e->getMessage()]);
        }
    }

    public function delete(LyricsDeleteRequest $request, LyricsRepository $lyricsRepo)
    {
        try
        {
            $lyricsRepo->delete($request->validated('id'));
            return $this->successResponse();
        }
        catch(Throwable $th)
        {
            return $this->validationErrorResponse($th->getMessage());
        }
    }

    public function update(LyricsUpdateRequest $request, LyricsRepository $lyricsRepo){
        try {
            $tag = new Lyrics($request->post('id'), $request->post('content'));
            $lyricsRepo->update($tag);
            return $this->successResponse();
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse($th->getMessage());
        }
    }
}