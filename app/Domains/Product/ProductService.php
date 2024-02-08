<?php

declare(strict_types=1);

namespace App\Domains\Product;

use App\GraphQL\Product\Queries\ProductOrderByEnum;
use App\Models\Currency;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Lunar\Models\Language;

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
        $priceFilter      = $filters['price'] ?? null;
        $currencyId       = $priceFilter['currencyId'] ?? Currency::getDefault()->id;
        $ratingFilter     = $filters['rating'] ?? null;

        $query = Product::query()
            ->when($nameFilter, fn (Builder $q, string $name) => $q->where('lunar_products.attribute_data->name', 'like', "%$name%")) // TODO meilisearch
            ->whereHas('variants', function (Builder $q) use ($attributeFilters, $priceFilter, $currencyId): void {
                foreach ($attributeFilters as $attributeFilter) {
                    $this->attributeDataFilterInValueContext($q, $attributeFilter['handle'], $attributeFilter['values']);
                }

                $min = $priceFilter['min'] ?? null;
                $max = $priceFilter['max'] ?? null;
                $q->whereHas('prices', fn (Builder $q) => $q
                    ->when($min, fn ($q) => $q->where('price', '>=', $min))
                    ->when($max, fn ($q) => $q->where('price', '<=', $max))
                    ->where('currency_id', $currencyId)
                );
            })
            ->with([
                'variants' => function ($q) use ($attributeFilters): void {
                    foreach ($attributeFilters as $attributeFilter) {
                        $this->attributeDataFilterInValueContext($q, $attributeFilter['handle'], $attributeFilter['values']); //TODO withWhereHas macro?
                    }
                },
                'variants.prices' => function (MorphMany $query) use ($currencyId): void {
                    $query->where('lunar_prices.currency_id', $currencyId);
                },
                'variants.images' => function (BelongsToMany $query): void {
                    $query->where('lunar_media_product_variant.primary', true);
                },
                'variants.prices.currency',
                'variants.prices.priceable', // TODO optimize?
            ])
        ;

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        $results          = $paginator->getCollection();
        $reorderedResults = match ($orderBy) {
            ProductOrderByEnum::NAME_ASC   => $results->sortBy(fn (Product $product) => $product->translateAttribute('name')),
            ProductOrderByEnum::NAME_DESC  => $results->sortByDesc(fn (Product $product) => $product->translateAttribute('name')),
            ProductOrderByEnum::PRICE_DESC => $results->sortByDesc(fn (Product $product) => $product->variants->first()?->prices->first()?->price->value),
            ProductOrderByEnum::PRICE_ASC  => $results->sortBy(fn (Product $product) => $product->variants->first()?->prices->first()?->price->value),
        };

        return $paginator->setCollection($reorderedResults);
    }

    private function attributeDataFilterInValueContext(HasMany|Builder $q, string $handle, array $values, array $meta = []): void
    {
        $lang = $meta['lang'] ?? Language::getDefault()->code;

        match ($handle) {
            'color' => $q->whereIn('attribute_data->'.$handle.'->value', $values),
            default => $q->whereIn('attribute_data->'.$handle.'->value->'.$lang, $values),
        };
    }
}
