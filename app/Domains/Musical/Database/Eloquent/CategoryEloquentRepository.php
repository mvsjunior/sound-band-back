<?php

namespace App\Domains\Musical\Database\Eloquent;

use App\Domains\Commons\Database\QueryExpression;
use App\Domains\Commons\Exceptions\DuplicateEntryException;
use App\Domains\Musical\Entities\Category;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class CategoryEloquentRepository 
{
    private Builder $db;

    public function __construct(){
        $this->db = DB::table('music_categories');
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

    public function findById(int $id): ?Category
    {
        $result = $this->db->select()->where('id',  '=', $id)->get()[0];
        $category = $result ? new Category($result->id, $result->name) : $result;
        return $category;
    }

    public function store(Category $category): Category
    {
        try{
            $id = $this->db->insertGetId(["name" => $category->name()]);
            return new Category($id, $category->name());
        }
        catch (\Illuminate\Database\QueryException $e) 
        {
            $errorCode = $e->errorInfo[1];

            if($errorCode == 1062)
            {
                throw new DuplicateEntryException("The category name '{$category->name()}' is already in use.");
            }

            throw new Exception($e->getMessage());
        }
    }

    public function delete(int $id): void
    {
        $this->db->delete($id);
    }

    public function update(Category $category): void
    {
        try {
            $this->db->where('id', '=', $category->id())->update(['name' => $category->name()]);
        }
        catch (\Illuminate\Database\QueryException $e) 
        {
            $errorCode = $e->errorInfo[1];

            if($errorCode == 1062)
            {
                throw new DuplicateEntryException("The category name '{$category->name()}' is already in use.");
            }

            throw new Exception($e->getMessage());
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
