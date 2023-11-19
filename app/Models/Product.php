<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Lunar\Models\Product as BaseProduct;

/** @method Builder withSlug */
class Product extends BaseProduct implements Translatable
{
    protected function scopeWithSlug(Builder $query): Builder
    {
        return $query
            ->select('lunar_products.*')
            ->join('lunar_urls', fn (JoinClause $join): JoinClause =>
                $join
                    ->on('lunar_urls.element_id', '=', 'lunar_products.id')
                    ->where('lunar_urls.element_type', '=', BaseProduct::class)
            );
    }
}
