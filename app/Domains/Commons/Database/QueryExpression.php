<?php

namespace App\Domains\Commons\Database;

class QueryExpression
{
    public array $expressions = [];

    public function add(QueryClausule $clausule): QueryExpression
    {
        $this->expressions[] = $clausule;
        return $this;
    }
}