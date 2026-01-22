<?php

namespace App\Domains\Musical\Http\Controllers;

use App\Domains\Musical\Actions\Category\DeleteCategoryAction;
use App\Domains\Musical\Actions\Category\ListCategoriesAction;
use App\Domains\Musical\Actions\Category\StoreCategoryAction;
use App\Domains\Musical\Actions\Category\UpdateCategoryAction;
use App\Domains\Commons\Exceptions\DuplicateEntryException;
use App\Domains\Commons\Http\ApiResponseTrait;
use App\Domains\Musical\Http\Requests\CategoryDeleteRequest;
use App\Domains\Musical\Http\Requests\CategoryStoreRequest;
use App\Domains\Musical\Http\Requests\CategoryUpdateRequest;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class CategoryController
{
    use ApiResponseTrait;
    
    public function index(Request $request, ListCategoriesAction $action)
    {
        $id = filter_var($request->get('id'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

        return $this->successResponse(
            $action->execute($request->get('name'), $id)
        );
    }

    public function store(CategoryStoreRequest $request, StoreCategoryAction $action)
    {
        try
        {
            $categoryDTO = $action->execute($request->validated('name'));
            return $this->successResponse($categoryDTO->toArray());
        }
        catch(DuplicateEntryException $e)
        {
            return $this->validationErrorResponse(['category.name.duplicate.entry' => $e->getMessage()]);
        }
    }

    public function delete(CategoryDeleteRequest $request, DeleteCategoryAction $action)
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

    public function update(CategoryUpdateRequest $request, UpdateCategoryAction $action){
        try {
            $action->execute($request->validated('id'), $request->validated('name'));
            return $this->successResponse();
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse($th->getMessage());
        }
    }
}
