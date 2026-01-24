<?php

namespace App\Domains\Musical\Database\Eloquent;

use App\Domains\Commons\Database\QueryExpression;
use App\Domains\Musical\Entities\Chord;
use App\Domains\Musical\Entities\ChordContent;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class ChordEloquentRepository
{
    private Builder $db;

    public function __construct()
    {
        $this->db = DB::table('chords');
    }

    public function all(?QueryExpression $expressions = null): array
    {
        return $this->baseQuery($expressions)
            ->get()
            ->map(fn ($row) => (array) $row)
            ->toArray();
    }

    public function paginate(?QueryExpression $expressions, int $page, int $perPage): array
    {
        $query = $this->baseQuery($expressions)->orderBy('chords.id');
        $total = (clone $query)->count();
        $items = $query->forPage($page, $perPage)->get()->map(fn ($row) => (array) $row)->toArray();

        return [
            'items' => $items,
            'pagination' => [
                'page' => $page,
                'perPage' => $perPage,
                'total' => $total,
                'lastPage' => $perPage > 0 ? (int) ceil($total / $perPage) : 0,
            ],
        ];
    }

    public function findById(int $id): ?array
    {
        $result = $this->db
            ->select([
                'chords.id',
                'chords.music_id',
                'chords.tone_id',
                'chords.version',
                'chord_contents.content as chord_content',
                'tones.name as tone_name',
                'tones.type as tone_type',
            ])
            ->join('tones', 'chords.tone_id', '=', 'tones.id')
            ->join('chord_contents', 'chords.chord_content_id', '=', 'chord_contents.id')
            ->where('chords.id', '=', $id)
            ->first();

        return $result ? (array) $result : null;
    }

    private function baseQuery(?QueryExpression $expressions = null): Builder
    {
        $query = $this->db
            ->select([
                'chords.id',
                'chords.music_id',
                'chords.tone_id',
                'chords.version',
                'tones.name as tone_name',
                'tones.type as tone_type',
            ])
            ->join('tones', 'chords.tone_id', '=', 'tones.id');

        if ($expressions) {
            foreach ($expressions->expressions as $clausule) {
                $query = !empty($clausule)
                    ? $query->where($clausule->column, $clausule->operator, $clausule->value)
                    : $query;
            }
        }

        return $query;
    }

    public function store(Chord $chord, ChordContent $content): array
    {
        return DB::transaction(function () use ($chord, $content) {
            $now = now();

            $contentId = DB::table('chord_contents')->insertGetId([
                'content' => $content->content(),
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $chordId = $this->db->insertGetId([
                'tone_id' => $chord->toneId(),
                'chord_content_id' => $contentId,
                'music_id' => $chord->musicId(),
                'version' => $chord->version(),
            ]);

            return $this->findById($chordId);
        });
    }

    public function update(Chord $chord, ChordContent $content): void
    {
        DB::transaction(function () use ($chord, $content) {
            $contentId = $this->db
                ->where('id', '=', $chord->id())
                ->value('chord_content_id');

            $this->db->where('id', '=', $chord->id())->update([
                'tone_id' => $chord->toneId(),
                'music_id' => $chord->musicId(),
                'version' => $chord->version(),
            ]);

            if ($contentId) {
                DB::table('chord_contents')->where('id', '=', $contentId)->update([
                    'content' => $content->content(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    public function delete(int $id): void
    {
        DB::transaction(function () use ($id) {
            $contentId = $this->db->where('id', '=', $id)->value('chord_content_id');

            $this->db->where('id', '=', $id)->delete();

            if ($contentId) {
                DB::table('chord_contents')->where('id', '=', $contentId)->delete();
            }
        });
    }
}
