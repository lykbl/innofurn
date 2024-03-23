<?php

declare(strict_types=1);

namespace App\Services;

use App\GraphQL\Queries\ProductVariant\ProductVariantOrderByEnum;
use App\Models\Collection as LunarCollection;
use App\Models\Currency;
use App\Models\ProductOption;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Scout\Builder as ScoutBuilder;
use Lunar\Models\Language;
use Meilisearch\Contracts\SearchQuery;
use Meilisearch\Endpoints\Indexes;

class ProductVariantService
{
    public function findProductVariantsForCollection(
        int $perPage,
        int $page,
        array $filters,
        ProductVariantOrderByEnum $orderBy,
    ): ScoutBuilder {
        $currencyCode = $priceFilter['currencyId'] ?? Currency::getDefault()->code;
        $langCode     = Language::getDefault()->code;

        $meiliSearchFilters = [];
        if ($collectionSlug = $filters['collection'] ?? '') {
            $collection = LunarCollection::whereHas(
                'urls',
                fn ($query) => $query->where('slug', $collectionSlug)
            )->firstOrFail();
            $collectionName       = $collection->translateAttribute('name', $langCode);
            $meiliSearchFilters[] = "collection_hierarchy.$collection->id = '$collectionName'";
        }

        if ($min = $filters['price']['min'] ?? null) {
            $meiliSearchFilters[] = "prices.$currencyCode >= $min";
        }
        if ($max = $filters['price']['max'] ?? null) {
            $meiliSearchFilters[] = "prices.$currencyCode <= $max";
        }
        if ($ratingFilter = $filters['rating'] ?? null) {
            $meiliSearchFilters[] = "rating >= $ratingFilter";
        }
        foreach ($filters['options'] ?? [] as $optionFilter) {
            $values = collect($optionFilter['values'] ?? []);
            if ($values->isEmpty()) {
                continue;
            }

            $formattedOptionValues = $values->map(fn ($value) => "'$value'")->join(',');
            $meiliSearchFilters[]  = "options.{$optionFilter['handle']} IN [$formattedOptionValues]";
        }
        if ($filters['onSaleOnly'] ?? false) {
            $meiliSearchFilters[] = 'on_sale = true';
        }

        $meiliSearchOrderBy = ["{$orderBy->key($currencyCode)}:{$orderBy->direction()}"];
        $search             = $filters['search'] ?? '';
        $results            = ProductVariant::search($search, function (Indexes $meiliSearch, string $search, array $baseOptions) use ($page, $perPage, $meiliSearchFilters, $meiliSearchOrderBy) {
            $searchQuery = new SearchQuery();
            $searchQuery->setQuery($search)
                ->setFilter($meiliSearchFilters)
                ->setPage($page ?? $baseOptions['page'] ?? 1)
                ->setHitsPerPage($perPage ?? $baseOptions['hitsPerPage'] ?? 25)
                ->setSort($meiliSearchOrderBy);

            return $meiliSearch->search($search, searchParams: $searchQuery->toArray());
        })->within("product_variants_index_$langCode");

        return $results;
    }

    public function findProductVariants(
        string $search,
        int $perPage,
        int $page,
    ): ScoutBuilder {
        // TODO fix add lang support
        $langCode = Language::getDefault()->code;

        $results = ProductVariant::search($search, function (Indexes $meiliSearch, string $search, array $baseOptions) use ($page, $perPage) {
            $meiliSearchOrderBy = ['name:desc'];
            $searchQuery        = new SearchQuery();
            $searchQuery->setQuery($search)
                ->setFacets(['collection_hierarchy'])
                ->setPage($page ?? $baseOptions['page'] ?? 1)
                ->setHitsPerPage($perPage ?? $baseOptions['hitsPerPage'] ?? 10)
                ->setSort($meiliSearchOrderBy);

            return $meiliSearch->search($search, searchParams: $searchQuery->toArray());
        })->within("product_variants_index_$langCode");

        return $results;
    }

    /**
     * @param string $collectionSlug
     *
     * @return Collection<ProductOption>
     */
    public function collectionFilters(string $collectionSlug): Collection
    {
        $rootCollection = LunarCollection::whereHas(
            'urls',
            fn ($query) => $query->where('slug', $collectionSlug)
        )->with(['descendants'])->firstOrFail();

        $collectionIds = [$rootCollection->id, ...$rootCollection->descendants->pluck('id')];
        $query         = ProductOption::whereHas(
            'values.variants.product.collections',
            fn ($query) => $query->whereIn('lunar_collections.id', $collectionIds)
        );

        return $query->get();
    }
}
