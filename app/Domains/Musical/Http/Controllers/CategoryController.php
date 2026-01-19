<?php

namespace App\Domains\Musical\Http\Controllers;

use App\Domains\Commons\Database\QueryClausule;
use App\Domains\Commons\Database\QueryExpression;
use App\Domains\Commons\Exceptions\DuplicateEntryException;
use App\Domains\Commons\Http\ApiResponseTrait;
use App\Domains\Musical\Database\CategoryRepository;
use App\Domains\Musical\DTO\CategoryDTO;
use App\Domains\Musical\Entities\Category;
use App\Domains\Musical\Http\Requests\CategoryDeleteRequest;
use App\Domains\Musical\Http\Requests\CategoryStoreRequest;
use App\Domains\Musical\Http\Requests\CategoryUpdateRequest;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class CategoryController
{
    use ApiResponseTrait;
    
    public function index(Request $request, CategoryRepository $tagRepo)
    {
        $expression = new QueryExpression();
        $expression = $request->get('name', null) ? $expression->add(new QueryClausule('name', 'LIKE', "%{$request->get('name','')}%")) : $expression;
        $expression = $request->get('id', null)   ? $expression->add(new QueryClausule("id", "=", $request->get('id')))                 : $expression;

        return $this->successResponse($tagRepo->all($expression));
    }

    public function store(CategoryStoreRequest $request, CategoryRepository $tagRepo)
    {
        try
        {
            $category = $tagRepo->store(new Category(null,$request->validated("name")));
            $tagDTO = new CategoryDTO($category->id(), $category->name());

            return $this->successResponse($tagDTO->toArray());
        }
        catch(DuplicateEntryException $e)
        {
            return $this->validationErrorResponse(['category.name.duplicate.entry' => $e->getMessage()]);
        }
    }

    public function delete(CategoryDeleteRequest $request, CategoryRepository $tagRepo)
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

    public function update(CategoryUpdateRequest $request, CategoryRepository $tagRepo){
        try {
            $category = new Category($request->post('id'), $request->post('name'));
            $tagRepo->update($category);
            return $this->successResponse();
        } catch (\Throwable $th) {
            //throw $th;
            return $this->errorResponse($th->getMessage());
        }
    }
}