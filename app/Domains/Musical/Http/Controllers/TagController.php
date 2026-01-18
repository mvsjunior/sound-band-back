<?php

namespace App\Domains\Musical\Http\Controllers;

use App\Domains\Commons\Database\QueryClausule;
use App\Domains\Commons\Database\QueryExpression;
use App\Domains\Commons\Exceptions\DuplicateEntryException;
use App\Domains\Commons\Http\ApiResponseTrait;
use App\Domains\Musical\Database\TagRepository;
use App\Domains\Musical\DTO\TagDTO;
use App\Domains\Musical\Entities\Tag;
use App\Domains\Musical\Http\Requests\DeleteTagRequest;
use App\Domains\Musical\Http\Requests\TagRequest;
use App\Domains\Musical\Http\Requests\UpdateTagRequest;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class TagController
{
    use ApiResponseTrait;
    
    public function index(Request $request, TagRepository $tagRepo)
    {
        $expression = new QueryExpression();
        $expression = $request->get('name', null) ? $expression->add(new QueryClausule('name', 'LIKE', "%{$request->get('name','')}%")) : $expression;
        $expression = $request->get('id', null)   ? $expression->add(new QueryClausule("id", "=", $request->get('id')))                 : $expression;

        return $this->successResponse($tagRepo->all($expression));
    }

    public function store(TagRequest $request, TagRepository $tagRepo)
    {
        try
        {
            $tag = $tagRepo->store(new Tag(null,$request->validated("name")));
            $tagDTO = new TagDTO($tag->id(), $tag->name());

            return $this->successResponse($tagDTO->toArray());
        }
        catch(DuplicateEntryException $e)
        {
            return $this->validationErrorResponse(['tag.name.duplicate.entry' => $e->getMessage()]);
        }
    }

    public function delete(DeleteTagRequest $request, TagRepository $tagRepo)
    {
        try
        {
            $tagRepo->delete($request->validated('id'));
            return $this->successResponse();
        }
        catch(Throwable $th)
        {
            return $this->validationErrorResponse($th->getMessage());
        }
    }

    public function update(UpdateTagRequest $request, TagRepository $tagRepo){
        try {
            $tag = new Tag($request->post('id'), $request->post('name'));
            $tagRepo->update($tag);
            return $this->successResponse();
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse($th->getMessage());
        }
    }
}