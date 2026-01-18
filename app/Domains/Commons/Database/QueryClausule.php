<?php

namespace App\Domains\Commons\Database;

class QueryClausule
{
    public function __construct(
        public readonly string $column,
        public readonly string $operator,
        public readonly string $value
    )
    {}
}