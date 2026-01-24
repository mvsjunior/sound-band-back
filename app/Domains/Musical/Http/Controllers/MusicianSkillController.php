<?php

namespace App\Domains\Musical\Http\Controllers;

use App\Domains\Commons\Exceptions\DuplicateEntryException;
use App\Domains\Commons\Http\ApiResponseTrait;
use App\Domains\Commons\Http\ResolvesPagination;
use App\Domains\Musical\Actions\MusicianSkill\DeleteMusicianSkillAction;
use App\Domains\Musical\Actions\MusicianSkill\ListMusicianSkillsAction;
use App\Domains\Musical\Actions\MusicianSkill\ShowMusicianSkillAction;
use App\Domains\Musical\Actions\MusicianSkill\StoreMusicianSkillAction;
use App\Domains\Musical\Actions\MusicianSkill\UpdateMusicianSkillAction;
use App\Domains\Musical\Http\Requests\MusicianSkillDeleteRequest;
use App\Domains\Musical\Http\Requests\MusicianSkillShowRequest;
use App\Domains\Musical\Http\Requests\MusicianSkillStoreRequest;
use App\Domains\Musical\Http\Requests\MusicianSkillUpdateRequest;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class MusicianSkillController
{
    use ApiResponseTrait;
    use ResolvesPagination;

    public function index(Request $request, ListMusicianSkillsAction $action)
    {
        $musicianId = filter_var($request->get('musicianId'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        $skillId = filter_var($request->get('skillId'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        [$page, $perPage] = $this->resolvePagination($request);

        return $this->successResponse(
            $action->execute($musicianId, $skillId, $page, $perPage)
        );
    }

    public function store(MusicianSkillStoreRequest $request, StoreMusicianSkillAction $action)
    {
        try {
            $musicianSkillDTO = $action->execute(
                $request->validated('musicianId'),
                $request->validated('skillId')
            );

            return $this->successResponse($musicianSkillDTO->toArray());
        } catch (DuplicateEntryException $e) {
            return $this->validationErrorResponse([
                'musicianSkill.duplicate.entry' => $e->getMessage(),
            ]);
        }
    }

    public function show(MusicianSkillShowRequest $request, ShowMusicianSkillAction $action)
    {
        $musicianSkillDTO = $action->execute(
            $request->validated('musicianId'),
            $request->validated('skillId')
        );

        if (!$musicianSkillDTO) {
            return $this->notFoundResponse('Musician skill not found');
        }

        return $this->successResponse($musicianSkillDTO->toArray());
    }

    public function update(MusicianSkillUpdateRequest $request, UpdateMusicianSkillAction $action)
    {
        try {
            $updated = $action->execute(
                $request->validated('musicianId'),
                $request->validated('skillId'),
                $request->validated('newMusicianId'),
                $request->validated('newSkillId')
            );

            if (!$updated) {
                return $this->notFoundResponse('Musician skill not found');
            }

            return $this->successResponse();
        } catch (DuplicateEntryException $e) {
            return $this->validationErrorResponse([
                'musicianSkill.duplicate.entry' => $e->getMessage(),
            ]);
        } catch (Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function delete(MusicianSkillDeleteRequest $request, DeleteMusicianSkillAction $action)
    {
        try {
            $action->execute(
                $request->validated('musicianId'),
                $request->validated('skillId')
            );

            return $this->successResponse();
        } catch (Throwable $th) {
            return $this->validationErrorResponse($th->getMessage());
        }
    }
}
