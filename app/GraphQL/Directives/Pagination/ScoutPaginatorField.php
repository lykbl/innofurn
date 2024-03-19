<?php

declare(strict_types=1);

namespace App\GraphQL\Directives\Pagination;

use Illuminate\Pagination\LengthAwarePaginator;

class ScoutPaginatorField
{
    /**
     * @param LengthAwarePaginator<mixed> $paginator
     *
     * @return array{
     *     count: int,
     *     currentPage: int,
     *     firstItem: int|null,
     *     hasMorePages: bool,
     *     lastItem: int|null,
     *     lastPage: int,
     *     perPage: int,
     *     total: int,
     *     facetDistribution: array{level: string, name: string, count: int}
     * }
     */
    public function paginatorInfoResolver(LengthAwarePaginator $paginator): array
    {
        return [
            'count'             => count($paginator->items()),
            'currentPage'       => $paginator->currentPage(),
            'firstItem'         => $paginator->firstItem(),
            'hasMorePages'      => $paginator->hasMorePages(),
            'lastItem'          => $paginator->lastItem(),
            'lastPage'          => $paginator->lastPage(),
            'perPage'           => $paginator->perPage(),
            'total'             => $paginator->total(),
            'facetDistribution' => $this->prepareFacetDistribution($paginator->getOptions()['facetDistribution'] ?? []),
        ];
    }

    /**
     * @param LengthAwarePaginator<mixed> $paginator
     *
     * @return array<int, mixed>
     */
    public function dataResolver(LengthAwarePaginator $paginator): array
    {
        return $paginator->items();
    }

    private function prepareFacetDistribution(array $facetDistribution): array
    {
        $formatted = [];

        foreach ($facetDistribution as $level => $facet) {
            foreach ($facet as $value => $count) {
                $formatted[] = [
                    'level' => $level,
                    'name'  => $value,
                    'count' => $count,
                ];
            }
        }

        return $formatted;
    }
}
