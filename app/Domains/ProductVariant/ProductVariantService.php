<?php

declare(strict_types=1);

namespace App\Domains\ProductVariant;

use App\GraphQL\ProductVariant\Queries\ProductVariantOrderByEnum;
use Illuminate\Database\Eloquent\Builder;

class ProductVariantService
{
    public function buildSearchQuery(
        array $optionsFilter = [],
        int $type = null,
        string $name = null,
        int $collection = null,
        string $brand = null,
        array $priceFilter = [], // TODO Class > array?
        ProductVariantOrderByEnum $orderBy = ProductVariantOrderByEnum::NAME_DESC,
    ): Builder {
        $query = ProductVariant::query()
            ->from('lunar_product_variants as lpv')
            ->select(['lpv.*'])
            ->join('lunar_products as lp', 'lpv.product_id', '=', 'lp.id')
            ->join('lunar_prices', 'lunar_prices.priceable_id', '=', 'lpv.id')
            ->when($type, function (Builder $query, string $type) {
                return $query->where('lp.product_type_id', $type);
            })
            ->when($name, function (Builder $query, $name) {
                // TODO meilisearch please
                return $query->where('lp.attribute_data->name', 'like', "%{$name}%");
            })
            ->when($collection, function (Builder $query, $collection) {
                return $query
                    ->join('lunar_collection_product as lcp', 'lcp.product_id', '=', 'lp.id')
                    ->where('lcp.collection_id', $collection);
            })
            ->when($brand, function (Builder $query, $brand) {
                return $query->where('lp.brand_id', $brand);
            })
            ->when($priceFilter, function (Builder $query) use ($priceFilter) {
                ['min' => $minPrice, 'max' => $maxPrice, 'currency' => $currencyId] = $priceFilter;

                return $query
                    ->where('lunar_prices.currency_id', $currencyId)
                    ->when($minPrice, fn (Builder $query) => $query->where('lunar_prices.price', '>=', $minPrice))
                    ->when($maxPrice, fn (Builder $query) => $query->where('lunar_prices.price', '<=', $maxPrice));
            })
            ->when($optionsFilter, function (Builder $query, $options) {
                $joined = $query
                    ->join('lunar_product_option_value_product_variant', 'lunar_product_option_value_product_variant.variant_id', '=', 'lpv.id')
                    ->join('lunar_product_option_values', 'lunar_product_option_values.id', '=', 'lunar_product_option_value_product_variant.value_id')
                    ->join('lunar_product_options', 'lunar_product_options.id', '=', 'lunar_product_option_values.product_option_id');
                foreach ($options as ['optionTypeId' => $id, 'valueIds' => $ids]) {
                    $joined
                        ->where('lunar_product_options.id', $id)
                        ->whereIn('lunar_product_option_values.id', $ids);
                }

                return $joined;
            })
        ;

        match ($orderBy->column()) {
            'name'  => $query->orderBy('lpv.attribute_data->name', $orderBy->direction()),
            'price' => $query->orderBy('lunar_prices.price', $orderBy->direction()),
        };

        return $query;
    }
}
