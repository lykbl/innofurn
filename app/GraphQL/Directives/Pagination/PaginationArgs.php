<?php

declare(strict_types=1);

namespace App\GraphQL\Directives\Pagination;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Laravel\Scout\Builder as ScoutBuilder;
use Nuwave\Lighthouse\Pagination\PaginationArgs as BasePaginationArgs;
use Nuwave\Lighthouse\Pagination\PaginationType as BasePaginationType;
use Nuwave\Lighthouse\Pagination\ZeroPerPageLengthAwarePaginator;
use Nuwave\Lighthouse\Pagination\ZeroPerPagePaginator;

class PaginationArgs extends BasePaginationArgs
{
    public function __construct(
        public int $page,
        public int $first,
        public PaginationType|BasePaginationType $type, // TODO is this worth it?
    ) {
        parent::__construct($page, $first, $type);
    }

    /**
     * {@inheritDoc}
     */
    public function applyToBuilder(QueryBuilder|ScoutBuilder|EloquentBuilder|Relation $builder): Paginator
    {
        if (0 === $this->first) {
            if ($this->type->isSimple()) {
                return new ZeroPerPagePaginator($this->page);
            }

            $total = $builder instanceof ScoutBuilder
                ? 0 // Laravel\Scout\Builder exposes no method to get the total count
                : $builder->count(); // @phpstan-ignore-line see Illuminate\Database\Query\Builder::count(), available as a mixin in the other classes

            return new ZeroPerPageLengthAwarePaginator($total, $this->page);
        }

        if ($this->type->isSimple()) {
            $methodName = 'simplePaginate';
        } elseif ($this->type->isScout()) {
            $rawPaginator   = $builder->paginateRaw($this->first, 'page', $this->page);
            $engineResponse = $rawPaginator->items();
            $ids            = Arr::flatten($engineResponse['hits']);
            $paginator      = new LengthAwarePaginator(
                $builder->model::find($ids),
                $rawPaginator->total(),
                $rawPaginator->perPage(),
                $rawPaginator->currentPage(),
                ['facetDistribution' => $engineResponse['facetDistribution']]
            );

            return $paginator;
        } else {
            $methodName = 'paginate';
        }

        if ($builder instanceof ScoutBuilder) {
            return $builder->{$methodName}($this->first, 'page', $this->page);
        }

        return $builder->{$methodName}($this->first, ['*'], 'page', $this->page);
    }
}
