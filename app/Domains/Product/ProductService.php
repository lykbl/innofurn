<?php

declare(strict_types=1);

namespace App\Domains\Product;

use App\Domains\Attributes\AggregatedIndexedAttributeValue;
use App\GraphQL\Product\Queries\ProductOrderByEnum;
use App\Models\Currency;

use function count;

use \Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
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
        $ratingFilter     = $filters['rating']['avg'] ?? null;
        $onSaleOnly       = $filters['onSaleOnly'] ?? false;

        $currencyId       = $priceFilter['currencyId'] ?? Currency::getDefault()->id;
        $langCode         = Language::getDefault()->code;
        $orderByDirection = str_contains($orderBy->value, 'ASC') ? 'asc' : 'desc';

        $variantsQuery = DB::query()
            ->from('lunar_products')
            ->select([
                'lunar_products.id as product_id',
                'lunar_product_variants.id as product_variant_id',
            ])
            ->join('lunar_product_variants', 'lunar_products.id', '=', 'lunar_product_variants.product_id')
            ->when($nameFilter, fn (Builder $q, string $name) => $q->where('lunar_products.attribute_data->name', 'like', "%$name%")) // TODO meilisearch
            ->when($priceFilter, function (Builder $q, array $priceFilter) use ($currencyId): void {
                $min = $priceFilter['min'] ?? null;
                $max = $priceFilter['max'] ?? null;
                $q
                    ->when($min, fn ($q) => $q->where('lunar_prices.price', '>=', $min))
                    ->when($max, fn ($q) => $q->where('lunar_prices.price', '<=', $max))
                    ->where('lunar_prices.currency_id', $currencyId)
                    ->join('lunar_prices', 'lunar_prices.priceable_id', '=', 'lunar_product_variants.id')
                    ->where('lunar_prices.priceable_type', \Lunar\Models\ProductVariant::class)
                ;
            })
            ->when($onSaleOnly, fn (Builder $q) => $q->where(fn ($q) => $q
                ->whereExists(fn (Builder $q) => $q
                    ->from('lunar_discount_purchasables as ldp_variants')
                    ->join('lunar_discounts as variant_discounts', 'ldp_variants.discount_id', '=', 'variant_discounts.id')
                    ->where('ldp_variants.purchasable_type', '=', \Lunar\Models\ProductVariant::class)
                    ->whereRaw('ldp_variants.purchasable_id = lunar_product_variants.id')
                    ->whereRaw('NOW() between variant_discounts.starts_at and variant_discounts.ends_at')
                )
                ->orWhereExists(fn ($q) => $q
                    ->from('lunar_discount_purchasables as ldp_products')
                    ->join('lunar_discounts as product_discounts', 'ldp_products.discount_id', '=', 'product_discounts.id')
                    ->where('ldp_products.purchasable_type', '=', \Lunar\Models\Product::class)
                    ->whereRaw('ldp_products.purchasable_id = lunar_product_variants.id')
                    ->whereRaw('NOW() between product_discounts.starts_at and product_discounts.ends_at')
                )
            ))
            ->when($attributeFilters, function (Builder $q, array $attributeFilters) use ($langCode): void {
                foreach ($attributeFilters as $attributeFilter) {
                    $this->attributeDataFilterInValueContext($q, $attributeFilter['handle'], $attributeFilter['values'], ['lang' => $langCode]);
                }
            })
            ->groupBy('product_id', 'product_variant_id')
        ;

        match ($orderBy) {
            ProductOrderByEnum::NAME_ASC, ProductOrderByEnum::NAME_DESC => $variantsQuery
                ->addSelect(
                    DB::raw("
                        if (JSON_CONTAINS_PATH(lunar_product_variants.attribute_data, 'one', '$.name.value.en'),
                            lunar_product_variants.attribute_data->>'$.name.value.en',
                            lunar_products.attribute_data->>'$.name.value'
                        ) as order_by_name
                    ")
                )
                ->orderBy('order_by_name', $orderByDirection)
            ,
            ProductOrderByEnum::PRICE_ASC, ProductOrderByEnum::PRICE_DESC => $variantsQuery
                ->addSelect(
                    DB::raw('
                        MAX(lunar_prices.price) as order_by_price
                    ')
                )
                ->orderBy('order_by_price', $orderByDirection)
            ,
            default => null,
        };

        if ($ratingFilter || ProductOrderByEnum::AVG_RATING === $orderBy) {
            $variantsQuery
                ->leftJoin('reviews as variant_reviews', fn ($j) => $j
                    ->on('lunar_product_variants.id', 'variant_reviews.reviewable_id')
                    ->where('variant_reviews.reviewable_type', \Lunar\Models\ProductVariant::class)
                )
                ->leftJoin('reviews as product_reviews', fn ($j) => $j
                    ->on('lunar_product_variants.id', 'variant_reviews.reviewable_id')
                    ->where('variant_reviews.reviewable_type', \Lunar\Models\Product::class)
                )
                ->addSelect(
                    DB::raw('
                        CASE
                            WHEN AVG(variant_reviews.rating) is null THEN AVG(product_reviews.rating)
                            ELSE AVG(variant_reviews.rating)
                        END as avg_rating
                    ')
                )
                ->when(ProductOrderByEnum::AVG_RATING === $orderBy, fn ($q) => $q->orderBy('avg_rating', $orderByDirection))
                ->when($ratingFilter, fn (Builder $q) => $q->having('avg_rating', '>=', $ratingFilter))
            ;
        }

        $idsQuery = DB::query()
            ->from('variants')
            ->withExpression('variants', $variantsQuery)
            ->select([
                'product_id',
                DB::raw('GROUP_CONCAT(product_variant_id) as product_variant_ids'),
            ])
            ->groupBy('product_id')
        ;

        $total = $idsQuery->count();
        $productRows = $idsQuery->limit($perPage)->offset(($page - 1) * $perPage)->get();

        $ids = $productRows->reduce(function ($carry, $item) {
            $carry['product_ids'][] = $item->product_id;

            array_push($carry['product_variant_ids'], ...explode(',', $item->product_variant_ids));

            return $carry;
        }, ['product_ids' => [], 'product_variant_ids' => []]);

        $products = Product::query()
            ->when($nameFilter, fn (Builder $q, string $name) => $q->where('lunar_products.attribute_data->name', 'like', "%$name%")) // TODO meilisearch
            ->with([
                'variants.images' => fn (BelongsToMany $q) => $q->where('lunar_media_product_variant.primary', true),
                'variants.prices.currency',
                'variants.prices.priceable', // TODO optimize?
                'variants.discounts',
                'discounts',
            ])
            ->with('variants', fn (HasMany $q) => $q->whereIn('lunar_product_variants.id', $ids['product_variant_ids']))
            ->whereIn('lunar_products.id', $ids['product_ids'])
        ;

        return new LengthAwarePaginator(
            $products->get(),
            $total,
            $perPage,
            $page,
        );
    }

    private function attributeDataFilterInValueContext(HasMany|Builder $q, string $handle, array $values, array $meta = []): void
    {
        $lang = $meta['lang'] ?? Language::getDefault()->code;
        if (0 === count($values)) {
            return;
        }

        match ($handle) {
            'color' => $q->whereIn('lunar_product_variants.attribute_data->'.$handle.'->value->label', $values),
            default => $q->whereIn('lunar_product_variants.attribute_data->'.$handle.'->value->'.$lang, $values),
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
