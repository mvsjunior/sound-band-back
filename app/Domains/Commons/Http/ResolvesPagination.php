<?php

namespace App\Domains\Commons\Http;

use Symfony\Component\HttpFoundation\Request;

trait ResolvesPagination
{
    private function resolvePagination(
        Request $request,
        int $defaultPerPage = 15,
        int $maxPerPage = 100
    ): array {
        $page = filter_var($request->get('page'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
        $perPage = filter_var($request->get('perPage'), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

        $page = ($page && $page > 0) ? $page : 1;
        $perPage = ($perPage && $perPage > 0) ? $perPage : $defaultPerPage;

        if ($perPage > $maxPerPage) {
            $perPage = $maxPerPage;
        }

        return [$page, $perPage];
    }
}
