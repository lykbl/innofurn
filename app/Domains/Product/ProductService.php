<?php

declare(strict_types=1);

namespace App\Domains\Product;

use App\Domains\Attributes\AggregatedIndexedAttributeValue;
use App\GraphQL\Product\Queries\ProductOrderByEnum;
use App\Models\Currency;

use function count;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

        $idsQuery = DB::query()
            ->from('lunar_products')
            ->select([
                'lunar_products.id as product_id',
                DB::RAW('GROUP_CONCAT(lunar_product_variants.id) as product_variant_ids'),
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
            ->when($ratingFilter, fn (Builder $q, array $ratingFilter) => $q
                ->withAvg('reviews', 'rating')
                ->having('reviews_avg_rating', '>=', $ratingFilter)
            )
            ->when($onSaleOnly, fn (Builder $q) => $q
                ->whereHas('variants.discounts')
                ->orWhereHas('discounts')
            )
            ->when($attributeFilters, function (Builder $q, array $attributeFilters) use ($langCode): void {
                foreach ($attributeFilters as $attributeFilter) {
                    $this->attributeDataFilterInValueContext($q, $attributeFilter['handle'], $attributeFilter['values'], ['lang' => $langCode]);
                }
            })
            ->groupBy('lunar_products.id')
            ->limit($perPage)
            ->offset(($page - 1) * $perPage)
        ;

        match ($orderBy) {
            ProductOrderByEnum::NAME_ASC, ProductOrderByEnum::NAME_DESC => $idsQuery
                ->addSelect(
                    DB::raw("
                        if (JSON_CONTAINS_PATH(lunar_product_variants.attribute_data, 'one', '$.name.value.en'),
                            lunar_product_variants.attribute_data->>'$.name.value.en',
                            lunar_products.attribute_data->>'$.name.value'
                        ) as order_by
                    ")
                )
            ,
            ProductOrderByEnum::PRICE_ASC, ProductOrderByEnum::PRICE_DESC => $idsQuery
                ->addSelect(
                    DB::raw('
                        MAX(lunar_prices.price) as order_by
                    ')
                )
        };
        $idsQuery->orderBy('order_by', $orderByDirection);
        $ids = $idsQuery->get()->reduce(function ($carry, $item) {
            $carry['product_ids'][] = $item->product_id;
            array_push($carry['product_variant_ids'], ...explode(',', $item->product_variant_ids));

            return $carry;
        }, ['product_ids' => [], 'product_variant_ids' => []]);

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
            ->with('variants', fn (HasMany $q) => $q->whereIn('lunar_product_variants.id', $ids['product_variant_ids']))
            ->whereIn('lunar_products.id', $ids['product_ids'])
        ;

        return $query->paginate(30, ['*'], 'page', 1);
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
