<?php

namespace App\Domains\Musical\Database\Eloquent;

use App\Domains\Commons\Database\QueryExpression;
use App\Domains\Musical\Entities\Lyrics;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class LyricsEloquentRepository 
{
    private Builder $db;

    public function __construct(){
        $this->db = DB::table('lyrics');
    }

    public function all(?QueryExpression $expressions = null): array
    {
        $query = $this->db->select();
        
        if($expressions){
            foreach($expressions->expressions as $key => $clausule){
                $query = !empty($clausule) ? $query->where($clausule->column, $clausule->operator, $clausule->value) : $query;
            }
        }

        return $query->get()->toArray();
    }

    public function paginate(?QueryExpression $expressions, int $page, int $perPage): array
    {
        $query = $this->db->select();

        if ($expressions) {
            foreach ($expressions->expressions as $clausule) {
                $query = !empty($clausule)
                    ? $query->where($clausule->column, $clausule->operator, $clausule->value)
                    : $query;
            }
        }

        $query->orderBy('id');
        $total = (clone $query)->count();
        $items = $query->forPage($page, $perPage)->get()->toArray();

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

    public function findById(int $id): ?Lyrics
    {
        $result = $this->db->select()->where('id',  '=', $id)->get()[0];
        $lyrics = $result ? new Lyrics($result->id, $result->content) : $result;
        return $lyrics;
    }

    public function store(Lyrics $lyrics): Lyrics
    {
        $id = $this->db->insertGetId(["content" => $lyrics->content()]);
        return new Lyrics($id, $lyrics->content());
    }

    public function delete(int $id): void
    {
        $this->db->delete($id);
    }

    public function update(Lyrics $lyrics): void
    {
        $this->db->where('id', '=', $lyrics->id())->update(['content' => $lyrics->content()]);
    }
}
