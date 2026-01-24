<?php

namespace App\Domains\Commons\DTO;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PaginationMetaDTO 
{
    public function __construct(
        public readonly string $page,
        public readonly string $perPage,
        public readonly int $total,
        public readonly int $lastPage
    ){}

    public static function createFromLaravelPaginator(LengthAwarePaginator $paginator){
        return new PaginationMetaDTO(
            $paginator->currentPage(),
            $paginator->perPage(),
            $paginator->total(),
            $paginator->lastPage()
        );
    }
}