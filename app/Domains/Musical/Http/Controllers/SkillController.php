<?php

namespace App\Domains\Musical\Http\Controllers;

use App\Domains\Commons\Exceptions\DuplicateEntryException;
use App\Domains\Commons\Http\ApiResponseTrait;
use App\Domains\Commons\Http\ResolvesPagination;
use App\Domains\Musical\Actions\Skill\DeleteSkillAction;
use App\Domains\Musical\Actions\Skill\ListSkillsAction;
use App\Domains\Musical\Actions\Skill\ShowSkillAction;
use App\Domains\Musical\Actions\Skill\StoreSkillAction;
use App\Domains\Musical\Actions\Skill\UpdateSkillAction;
use App\Domains\Musical\Http\Requests\SkillDeleteRequest;
use App\Domains\Musical\Http\Requests\SkillShowRequest;
use App\Domains\Musical\Http\Requests\SkillStoreRequest;
use App\Domains\Musical\Http\Requests\SkillUpdateRequest;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class SkillController
{
    use ApiResponseTrait;
    use ResolvesPagination;

    public function index(Request $request, ListSkillsAction $action)
    {
        $id = filter_var($request->get('id'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        [$page, $perPage] = $this->resolvePagination($request);

        return $this->successResponse(
            $action->execute($request->get('name'), $id, $page, $perPage)
        );
    }

    public function store(SkillStoreRequest $request, StoreSkillAction $action)
    {
        try {
            $skillDTO = $action->execute($request->validated('name'));

            return $this->successResponse($skillDTO->toArray());
        } catch (DuplicateEntryException $e) {
            return $this->validationErrorResponse(['skill.name.duplicate.entry' => $e->getMessage()]);
        }
    }

    public function show(SkillShowRequest $request, ShowSkillAction $action)
    {
        $skillDTO = $action->execute($request->validated('id'));

        if (!$skillDTO) {
            return $this->notFoundResponse('Skill not found');
        }

        return $this->successResponse($skillDTO->toArray());
    }

    public function update(SkillUpdateRequest $request, UpdateSkillAction $action)
    {
        try {
            $updated = $action->execute(
                $request->validated('id'),
                $request->validated('name')
            );

            if (!$updated) {
                return $this->notFoundResponse('Skill not found');
            }

            return $this->successResponse();
        } catch (DuplicateEntryException $e) {
            return $this->validationErrorResponse(['skill.name.duplicate.entry' => $e->getMessage()]);
        } catch (Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function delete(SkillDeleteRequest $request, DeleteSkillAction $action)
    {
        try {
            $action->execute($request->validated('id'));

            return $this->successResponse();
        } catch (Throwable $th) {
            return $this->validationErrorResponse($th->getMessage());
        }
    }
}
