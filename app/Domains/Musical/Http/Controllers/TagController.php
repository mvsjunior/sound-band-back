<?php

namespace App\Domains\Musical\Http\Controllers;

use App\Domains\Musical\Actions\Tag\DeleteTagAction;
use App\Domains\Musical\Actions\Tag\ListTagsAction;
use App\Domains\Musical\Actions\Tag\StoreTagAction;
use App\Domains\Musical\Actions\Tag\UpdateTagAction;
use App\Domains\Commons\Exceptions\DuplicateEntryException;
use App\Domains\Commons\Http\ApiResponseTrait;
use App\Domains\Musical\Http\Requests\DeleteTagRequest;
use App\Domains\Musical\Http\Requests\TagRequest;
use App\Domains\Musical\Http\Requests\UpdateTagRequest;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class TagController
{
    use ApiResponseTrait;
    
    public function index(Request $request, ListTagsAction $action)
    {
        $id = filter_var($request->get('id'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

        return $this->successResponse(
            $action->execute($request->get('name'), $id)
        );
    }

    public function store(TagRequest $request, StoreTagAction $action)
    {
        try
        {
            $tagDTO = $action->execute($request->validated('name'));
            return $this->successResponse($tagDTO->toArray());
        }
        catch(DuplicateEntryException $e)
        {
            return $this->validationErrorResponse(['tag.name.duplicate.entry' => $e->getMessage()]);
        }
    }

    public function delete(DeleteTagRequest $request, DeleteTagAction $action)
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

    public function update(UpdateTagRequest $request, UpdateTagAction $action){
        try {
            $action->execute($request->validated('id'), $request->validated('name'));
            return $this->successResponse();
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse($th->getMessage());
        }
    }
}
