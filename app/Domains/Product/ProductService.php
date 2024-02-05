<?php

declare(strict_types=1);

namespace App\Domains\Product;

use App\GraphQL\Product\Queries\ProductOrderByEnum;
use App\Models\Currency;
use App\Models\Price;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;

class ProductService
{
    /**
     * @param User               $user
     * @param array              $filters
     * @param ProductOrderByEnum $orderBy
     *
     * @return Builder
     */
    public function buildSearchQuery(
        Authenticatable $user,
        array $filters = [],
        ProductOrderByEnum $orderBy = ProductOrderByEnum::NAME_DESC,
    ) {
        $userCurrencyId = $user->retailCustomer->currency_id ?? Currency::getDefault()->id; // TODO add settings to customers

        $nameFilter       = $filters['name'] ?? null;
        $priceFilter      = $filters['price'] ?? null;
        $attributeFilters = $filters['attributes'] ?? [];
        $ratingFilter     = $filters['rating'] ?? null;

        $query = Product::query()
            ->whereHas(
                'variants', function (Builder $q) use ($priceFilter, $userCurrencyId): void {
                    if (!$priceFilter) {
                        return;
                    }

                    $min = $priceFilter['min'] ?? null;
                    $max = $priceFilter['max'] ?? null;
                    $q->whereHas('prices', fn (Builder $q) => $q
                        ->when($min, fn ($q) => $q->where('lunar_prices.price', '>=', $min))
                        ->when($max, fn ($q) => $q->where('lunar_prices.price', '<=', $max))
                        ->where('lunar_prices.currency_id', $userCurrencyId)
                    )
                    ;
                },
            )->with([
                'variants.images' => function ($query): void {
                    $query->where('lunar_media_product_variant.primary', true);
                },
                'variants.prices.currency',
                'variants.prices.priceable', // TODO optimize
            ])
        ;

        //        $query = match ($orderBy->column()) {
        //            'name'  => $query->orderBy('lunar_products.attribute_data->name', $orderBy->direction()),
        //            'price' => $query->orderBy('variants.prices.price', $orderBy->direction()),
        //            default => '',
        //        };

        return $query;
    }
}
