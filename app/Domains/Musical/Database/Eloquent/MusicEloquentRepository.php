<?php

namespace App\Domains\Musical\Database\Eloquent;

use App\Domains\Commons\Database\QueryExpression;
use App\Domains\Musical\Entities\Category;
use App\Domains\Musical\Entities\Lyrics;
use App\Domains\Musical\Entities\Music;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class MusicEloquentRepository 
{
    private Builder $db;

    public function __construct(){
        $this->db = DB::table('musics');
    }

    public function all(?QueryExpression $expressions = null): array
    {
        $query = $this->db->select([
            "musics.id",
            "musics.name",
            "musics.artist",
            "musics.lyrics_id",
            "lyrics.content as lyrics_content",
            "musics.category_id",
            "music_categories.name AS category_name",
        ]);
        
        if($expressions){
            foreach($expressions->expressions as $key => $clausule){
                $query = !empty($clausule) ? $query->where($clausule->column, $clausule->operator, $clausule->value) : $query;
            }
        }

        $query->leftJoin('lyrics', 'musics.lyrics_id', '=', 'lyrics.id');
        $query->join('music_categories', 'musics.category_id', '=', 'music_categories.id');

        $arrayMusics = $query->get()->toArray();

        if (empty($arrayMusics)) {
            return [];
        }

        $musicIds = array_map(fn ($music) => $music->id, $arrayMusics);

        $tags = DB::table('musics_tags')
            ->select(['musics_tags.music_id', 'tags.id', 'tags.name'])
            ->join('tags', 'musics_tags.tag_id', '=', 'tags.id')
            ->whereIn('musics_tags.music_id', $musicIds)
            ->get()
            ->toArray();

        $tagsByMusic = [];
        foreach ($tags as $tag) {
            $tagsByMusic[$tag->music_id][] = [
                'id' => $tag->id,
                'name' => $tag->name,
            ];
        }

        return array_map(
            function($music) use($tagsByMusic) {
                $musicArray = (array) $music;
                $musicArray['tags'] = $tagsByMusic[$music->id] ?? [];
                return $musicArray;
            },
            $arrayMusics
        );
    }

    public function findById(int $id): ?Music
    {
        $result = $this->db->select([
            "musics.id",
            "musics.name",
            "musics.artist",
            "musics.lyrics_id",
            "musics.category_id",
        ])->where('musics.id',  '=', $id)->first();

        if (!$result) {
            return null;
        }

        $lyrics = new Lyrics($result->lyrics_id, '');
        $category = new Category($result->category_id, '');

        return new Music(
            $result->id,
            $result->name,
            $result->artist ?? '',
            $lyrics,
            $category
        );
    }

    public function store(Music $music): Music
    {
        $id = $this->db->insertGetId(
            [
                "name" => $music->name(),
                "artist" => $music->artist(),
                "lyrics_id" => $music->lyrics()->id(),
                "category_id" => $music->category()->id()
            ]
        );

        return new Music(
            $id, 
            $music->name(),
            $music->artist(),
            $music->lyrics(),
            $music->category()
        );
    }

    public function delete(int $id): void
    {
        $this->db->where('id', '=', $id)->delete();
    }

    public function update(Music $music): void
    {
        $this->db->where('id', '=', $music->id())->update([
            'name' => $music->name(),
            'artist' => $music->artist(),
            'lyrics_id' => $music->lyrics()->id(),
            'category_id' => $music->category()->id(),
        ]);
    }

    public function syncTags(int $musicId, array $tagIds): void
    {
        $tagIds = array_values(array_unique(array_map('intval', $tagIds)));

        $pivot = DB::table('musics_tags');
        $pivot->where('music_id', '=', $musicId)->delete();

        if (empty($tagIds)) {
            return;
        }

        $now = now();
        $rows = array_map(
            fn ($tagId) => [
                'music_id' => $musicId,
                'tag_id' => $tagId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            $tagIds
        );

        $pivot->insert($rows);
    }
}
