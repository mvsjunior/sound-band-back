<?php

namespace App\Domains\Commons\DTO;

class PaginationMetaDTO 
{
    public function __construct(
        public readonly string $page,
        public readonly string $perPage,
        public readonly int $total,
        public readonly int $lastPage
    ){}
}