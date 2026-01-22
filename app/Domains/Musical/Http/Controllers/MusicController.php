<?php

namespace App\Domains\Musical\Http\Controllers;

use App\Domains\Commons\Database\QueryClausule;
use App\Domains\Commons\Database\QueryExpression;
use App\Domains\Commons\Http\ApiResponseTrait;
use App\Domains\Musical\Database\CategoryRepository;
use App\Domains\Musical\Database\LyricsRepository;
use App\Domains\Musical\Database\MusicRepository;
use App\Domains\Musical\Entities\Lyrics;
use App\Domains\Musical\Entities\Music;
use App\Domains\Musical\Http\Requests\MusicDeleteRequest;
use App\Domains\Musical\Http\Requests\MusicStoreRequest;
use App\Domains\Musical\Http\Requests\MusicUpdateRequest;
use App\Domains\Musical\Mappers\MusicDTOMapper;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class MusicController
{
    use ApiResponseTrait;
    
    public function index(Request $request, MusicRepository $musicRepo)
    {
        $expression = new QueryExpression();
        $expression = $request->get('name', null) ? $expression->add(new QueryClausule('musics.name', 'LIKE', "%{$request->get('name','')}%")) : $expression;
        $expression = $request->get('artist', null) ? $expression->add(new QueryClausule('musics.artist', 'LIKE', "%{$request->get('artist','')}%")) : $expression;
        $expression = $request->get('categoryId', null) ? $expression->add(new QueryClausule('musics.category_id', '=', $request->get('categoryId'))) : $expression;
        $expression = $request->get('id', null) ? $expression->add(new QueryClausule('musics.id', '=', $request->get('id'))) : $expression;

        $musicDTOs = array_map(
            fn (array $music) => MusicDTOMapper::fromArray($music),
            $musicRepo->all($expression)
        );

        return $this->successResponse($musicDTOs);
    }

    public function store(
        MusicStoreRequest $request,
        MusicRepository $musicRepo,
        LyricsRepository $lyricsRepo,
        CategoryRepository $categoryRepo
    )
    {
        try
        {
            $lyrics = $lyricsRepo->store(new Lyrics(null, $request->validated("lyrics")));
            $category = $categoryRepo->findById($request->validated("categoryId"));

            $music = new Music(
                null,
                $request->validated("name"),
                $request->validated("artist"),
                $lyrics,
                $category
            );

            $music = $musicRepo->store($music);

            $tagIds = $request->validated("tagIds", []);
            if (!empty($tagIds)) {
                $musicRepo->syncTags($music->id(), $tagIds);
            }

            $expression = (new QueryExpression())
                ->add(new QueryClausule('musics.id', '=', $music->id()));

            $musicData = $musicRepo->all($expression);
            $musicDTO = !empty($musicData) ? MusicDTOMapper::fromArray($musicData[0]) : null;

            return $this->successResponse($musicDTO);
        }
        catch(\Throwable $th)
        {
            return $this->errorResponse($th->getMessage());
        }
    }

    public function delete(MusicDeleteRequest $request, MusicRepository $musicRepo)
    {
        try
        {
            $musicRepo->delete($request->validated('id'));
            return $this->successResponse();
        }
        catch(Throwable $th)
        {
            return $this->validationErrorResponse($th->getMessage());
        }
    }

    public function update(
        MusicUpdateRequest $request,
        MusicRepository $musicRepo,
        LyricsRepository $lyricsRepo,
        CategoryRepository $categoryRepo
    ){
        try {
            $musicId = $request->validated('id');
            $existingMusic = $musicRepo->findById($musicId);

            if (!$existingMusic) {
                return $this->notFoundResponse('Music not found');
            }

            $lyricsId = $existingMusic->lyrics()->id();
            if ($lyricsId) {
                $lyrics = new Lyrics($lyricsId, $request->validated('lyrics'));
                $lyricsRepo->update($lyrics);
            } else {
                $lyrics = $lyricsRepo->store(new Lyrics(null, $request->validated('lyrics')));
            }

            $category = $categoryRepo->findById($request->validated('categoryId'));

            $music = new Music(
                $musicId,
                $request->validated('name'),
                $request->validated('artist'),
                $lyrics,
                $category
            );
            $musicRepo->update($music);

            if ($request->has('tagIds')) {
                $musicRepo->syncTags($musicId, $request->validated('tagIds', []));
            }

            return $this->successResponse();
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage());
        }
    }
}
