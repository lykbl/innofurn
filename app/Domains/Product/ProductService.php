<?php

declare(strict_types=1);

namespace App\Domains\Product;

use App\GraphQL\Product\Queries\ProductOrderByEnum;
use App\Models\Currency;
use App\Models\Price;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProductService
{
    public function findProducts(
        int $perPage,
        int $page,
        array $filters,
        ProductOrderByEnum $orderBy,
    ): LengthAwarePaginator {
        $nameFilter       = $filters['name'] ?? null;
        $attributeFilters = $filters['attributes'] ?? [];
        $ratingFilter     = $filters['rating'] ?? null;

        $priceFilter      = $filters['price'] ?? null;
        $currencyId = $priceFilter['currencyId'] ?? Currency::getDefault()->id;

        $query = Product::query()
            ->whereHas(
                'variants', function (Builder $q) use ($priceFilter, $currencyId): void {
                    if (!$priceFilter) {
                        return;
                    }

                    $min = $priceFilter['min'] ?? null;
                    $max = $priceFilter['max'] ?? null;
                    $q->whereHas('prices', fn (Builder $q) => $q
                        ->when($min, fn ($q) => $q->where('lunar_prices.price', '>=', $min))
                        ->when($max, fn ($q) => $q->where('lunar_prices.price', '<=', $max))
                        ->where('lunar_prices.currency_id', $currencyId)
                    );
                },
            )->with([
                'variants.prices' => function ($query) {
                    $query->where('lunar_prices.currency_id', Currency::getDefault()->id);
                },
                'variants.images' => function ($query): void {
                    $query->where('lunar_media_product_variant.primary', true);
                },
                'variants.prices.currency',
                'variants.prices.priceable', // TODO optimize
            ])
        ;


        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        $results = $paginator->getCollection();
        $reorderedResults = match ($orderBy) {
            ProductOrderByEnum::NAME_ASC => $results->sortBy(fn(Product $product) => $product->translateAttribute('name')),
            ProductOrderByEnum::NAME_DESC => $results->sortByDesc(fn(Product $product) => $product->translateAttribute('name')),
            ProductOrderByEnum::PRICE_DESC => $results->sortByDesc(fn(Product $product) => $product->variants->first()->prices->first()->price->value),
            ProductOrderByEnum::PRICE_ASC => $results->sortBy(fn(Product $product) => $product->variants->first()->prices->first()->price->value),
        };

        return $paginator->setCollection($reorderedResults);
    }
}
