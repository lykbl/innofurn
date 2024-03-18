<?php

declare(strict_types=1);

namespace App\Services;

use App\GraphQL\Exceptions\Product\ProductNotFoundException;
use App\GraphQL\Queries\Product\ProductOrderByEnum;
use App\Models\Collection as LunarCollection;
use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductVariant;
use App\Models\Url;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Lunar\Models\Language;
use Meilisearch\Contracts\SearchQuery;
use Meilisearch\Endpoints\Indexes;

class ProductService
{
    public function findProducts(
        int $perPage,
        int $page,
        array $filters,
        ProductOrderByEnum $orderBy,
    ): LengthAwarePaginator {
        $currencyCode = $priceFilter['currencyId'] ?? Currency::getDefault()->code;
        $langCode     = Language::getDefault()->code;

        $search         = $filters['search'] ?? '';
        $collectionSlug = $filters['collection'] ?? '';
        $collection     = LunarCollection::whereHas(
            'urls',
            fn ($query) => $query->where('slug', $collectionSlug)
        )->with(['descendants'])->firstOrFail();
        $collectionName  = $collection->translateAttribute('name', $langCode);
        $collectionLevel = $collection->ancestors->count();

        $meiliSearchFilters = [
            "collection_hierarchy.lvl_$collectionLevel = '$collectionName'",
        ];
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

            $meiliSearchFilters[] = "options.{$optionFilter['handle']} IN ['{$values->join(',')}']";
        }
        if ($filters['onSaleOnly'] ?? false) {
            $meiliSearchFilters[] = 'onSale = true';
        }

        $meiliSearchOrderBy = ["{$orderBy->key($currencyCode)}:{$orderBy->direction()}"];
        $results            = ProductVariant::search($search, function (Indexes $meiliSearch, string $search, array $baseOptions) use ($page, $perPage, $meiliSearchFilters, $meiliSearchOrderBy) {
            $searchQuery = new SearchQuery();
            $searchQuery->setQuery($search);
            $searchQuery->setFilter($meiliSearchFilters);
            $searchQuery->setPage($page ?? $baseOptions['page'] ?? 1);
            $searchQuery->setHitsPerPage($perPage ?? $baseOptions['hitsPerPage'] ?? 25);
            $searchQuery->setSort($meiliSearchOrderBy);

            return $meiliSearch->search($search, searchParams: $searchQuery->toArray());
        })->within("product_variants_index_$langCode")->paginate($perPage, 'page', $page);

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

    public function findBySlug(string $slug): Product
    {
        $url = Url::firstWhere(['slug' => $slug, 'element_type' => \Lunar\Models\Product::class]);
        if (!$url) {
            throw new ProductNotFoundException();
        }

        return $url->element;
    }
}
