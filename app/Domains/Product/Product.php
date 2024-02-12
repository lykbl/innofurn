<?php

declare(strict_types=1);

namespace App\Domains\Product;

use App\Models\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Lunar\Models\Discount;
use Lunar\Models\Product as BaseProduct;

/** @method Builder withSlug */
class Product extends BaseProduct implements Translatable
{
    protected function scopeWithSlug(Builder $query): Builder
    {
        return $query
            ->select('lunar_products.*')
            ->join('lunar_urls', fn (JoinClause $join): JoinClause => $join
                ->on('lunar_urls.element_id', '=', 'lunar_products.id')
                ->where('lunar_urls.element_type', '=', BaseProduct::class)
            );
    }

    public function discounts()
    {
        // TODO add active scope
        return $this
            ->hasManyThrough(
                Discount::class,
                \Lunar\Models\DiscountPurchasable::class,
                'purchasable_id',
                'id',
                'id',
                'discount_id'
            )
            ->where('lunar_discount_purchasables.purchasable_type', \Lunar\Models\Product::class);
    }
}
