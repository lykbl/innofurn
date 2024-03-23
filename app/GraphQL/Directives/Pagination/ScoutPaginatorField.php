<?php

declare(strict_types=1);

namespace App\GraphQL\Directives\Pagination;

use App\Models\FacetDistribution;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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

    /**
     * @param array<string, array<string, int>> $facetDistribution
     *
     * @return Collection<FacetDistribution>
     */
    private function prepareFacetDistribution(array $facetDistribution): Collection
    {
        $facetDistributionCollection = collect();

        foreach ($facetDistribution as $key => $facet) {
            // TODO fix this
            if (!str_contains($key, '.')) {
                continue;
            }
            [$path, $collectionId] = explode('.', $key);

            foreach ($facet as $count) {
                $facetDistributionCollection->add(new FacetDistribution([
                    'count'         => $count,
                    'collection_id' => $collectionId,
                ]));
            }
        }

        return $facetDistributionCollection;
    }
}
