<?php

namespace App\Domains\Musical\Database\Eloquent;

use App\Domains\Commons\Database\QueryExpression;
use App\Domains\Commons\Exceptions\DuplicateEntryException;
use App\Domains\Musical\Entities\Tag;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class TagEloquentRepository 
{
    private Builder $db;

    public function __construct(){
        $this->db = DB::table('tags');
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

    public function findById(int $id): ?Tag
    {
        $result = $this->db->select()->where('id',  '=', $id)->get()[0];
        $tag = $result ? new Tag($result->id, $result->name) : $result;
        return $tag;
    }

    public function store(Tag $tag): Tag
    {
        try{
            $id = $this->db->insertGetId(["name" => $tag->name()]);
            return new Tag($id, $tag->name());
        }
        catch (\Illuminate\Database\QueryException $e) 
        {
            $errorCode = $e->errorInfo[1];

            if($errorCode == 1062)
            {
                throw new DuplicateEntryException("The tag name '{$tag->name()}' is already in use.");
            }

            throw new Exception($e->getMessage());
        }
    }

    public function delete(int $id): void
    {
        $this->db->delete($id);
    }

    public function update(Tag $tag): void
    {
        try {
            $this->db->where('id', '=', $tag->id())->update(['name' => $tag->name()]);
        }
        catch (\Illuminate\Database\QueryException $e) 
        {
            $errorCode = $e->errorInfo[1];

            if($errorCode == 1062)
            {
                throw new DuplicateEntryException("The tag name '{$tag->name()}' is already in use.");
            }

            throw new Exception($e->getMessage());
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}