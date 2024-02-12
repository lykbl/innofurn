<?php

declare(strict_types=1);

namespace App\Domains\Product;

use App\Domains\Attributes\AggregatedIndexedAttributeValue;
use App\Domains\ProductVariant\ProductVariant;
use App\GraphQL\Product\Queries\ProductOrderByEnum;
use App\Models\Currency;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;
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
        $onSaleOnly       = $filters['onSaleOnly'] ?? false;

        $query = Product::query()
            ->when($nameFilter, fn (Builder $q, string $name) => $q->where('lunar_products.attribute_data->name', 'like', "%$name%")) // TODO meilisearch
            // TODO why the hell putting this after withWhereHas('variants') doubles agg queries?
            ->with([
                'variants.images' => fn (BelongsToMany $q) => $q->where('lunar_media_product_variant.primary', true),
                'variants.prices.currency',
                'variants.prices.priceable', // TODO optimize?
                'variants.discounts',
                'discounts',
            ])
            ->withWhereHas('variants.prices', function (MorphMany|Builder $q) use ($priceFilter, $currencyId): void {
                $min = $priceFilter['min'] ?? null;
                $max = $priceFilter['max'] ?? null;
                $q
                    ->when($min, fn ($q) => $q->where('lunar_prices.price', '>=', $min))
                    ->when($max, fn ($q) => $q->where('lunar_prices.price', '<=', $max))
                    ->where('lunar_prices.currency_id', $currencyId)
                ;
            })
            ->withWhereHas('variants', function ($q) use ($attributeFilters, $ratingFilter): void {
                foreach ($attributeFilters as $attributeFilter) {
                    $this->attributeDataFilterInValueContext($q, $attributeFilter['handle'], $attributeFilter['values']);
                }

                $avgRating = $ratingFilter['avg'] ?? null;
                $q->when($avgRating, fn (Builder $q) => $q
                    ->withAvg('reviews', 'rating')
                    ->having('reviews_avg_rating', '>=', $avgRating)
                );
            })
            ->when($onSaleOnly, fn (Builder $q) => $q
                ->whereHas('variants.discounts')
                ->orWhereHas('discounts')
            )
        ;

        $paginator        = $query->paginate($perPage, ['*'], 'page', $page);
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

    public function collectionFilters(int $collectionId, string $langCode = 'en')
    {
        $query = AggregatedIndexedAttributeValue::query()
            ->select([
                DB::raw('JSON_ARRAYAGG(value) as "values"'),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(lunar_attributes.name, '$.$langCode')) as label"), // TODO add fallback label
                'lunar_attributes.type as type',
                'lunar_attributes.handle as handle',
            ])
            ->join('lunar_attributables', 'indexed_product_attribute_values.attributable_id', '=', 'lunar_attributables.id')
            ->join('lunar_attributes', 'lunar_attributables.attribute_id', '=', 'lunar_attributes.id')
            ->where('product_type_id', $collectionId)
            ->groupBy('lunar_attributes.type', 'lunar_attributes.handle', 'label')
        ;

        $models = $query->get();

        return $models;
    }
}
