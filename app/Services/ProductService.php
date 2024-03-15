<?php

declare(strict_types=1);

namespace App\Services;

use App\GraphQL\Exceptions\Product\ProductNotFoundException;
use App\GraphQL\Queries\Product\ProductOrderByEnum;
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

        $collectionSlug = $filters['collection'] ?? null;
        $search         = $filters['search'];
        $optionFilters  = $filters['options'] ?? [];
        $priceFilter    = $filters['price'] ?? null;
        $ratingFilter   = $filters['rating'] ?? null;
        $onSaleOnly     = $filters['onSaleOnly'] ?? false;

        $meiliSearchFilters = [
            "collection_slug = $collectionSlug",
        ];
        if ($min = $priceFilter['min'] ?? null) {
            $meiliSearchFilters[] = "prices.$currencyCode >= $min";
        }
        if ($max = $priceFilter['max'] ?? null) {
            $meiliSearchFilters[] = "prices.$currencyCode <= $max";
        }
        if ($ratingFilter) {
            $meiliSearchFilters[] = "rating >= $ratingFilter";
        }
        foreach ($optionFilters as $handle => $value) {
            $meiliSearchFilters[] = "options.$handle.$langCode = $value";
        }
        if ($onSaleOnly) {
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
        })->paginate($perPage, 'page', $page);

        return $results;
    }

    /**
     * @param int $collectionId
     *
     * @return Collection<ProductOption>
     */
    public function collectionFilters(string $collectionSlug): Collection
    {
        $query = ProductOption::query()
            ->select('lunar_product_options.*')
            ->distinct()
            ->join('lunar_product_option_values', 'lunar_product_option_values.product_option_id', '=', 'lunar_product_options.id')
            ->join('lunar_product_option_value_product_variant', 'lunar_product_option_value_product_variant.value_id', '=', 'lunar_product_option_values.id')
            ->join('lunar_product_variants', 'lunar_product_variants.id', '=', 'lunar_product_option_value_product_variant.variant_id')
            ->join('lunar_products', 'lunar_products.id', '=', 'lunar_product_variants.product_id')
            ->join('lunar_collection_product', 'lunar_collection_product.product_id', '=', 'lunar_products.id')
            // TODO is this really needed lol?
            ->withRecursiveExpression('recursive_hierarchy', \App\Models\Collection::recursiveChildrenQuery()
                ->join('lunar_urls', 'lunar_urls.element_id', '=', 'root.id')
                ->where('lunar_urls.element_type', '=', \Lunar\Models\Collection::class)
                ->where('lunar_urls.slug', '=', $collectionSlug)
            )
            ->whereIn('lunar_collection_product.collection_id', function ($query): void {
                $query->select('id')->from('recursive_hierarchy');
            })
        ;

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
