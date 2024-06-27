<?php

namespace App\Helpers;

class PaginationHelper
{
    public static function paginate($query, $options)
    {
        $paginate = $options['paginate'] ?? true;
        $currentPage = $options['current_page'] ?? 1;
        $perPage = $options['perPage'] ?? 20;

        if ($paginate) {
            \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            return $query->paginate($perPage);
        }

        return $query->get();
    }
}
