<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Review\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
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
        return $this
            ->hasManyThrough(
                Discount::class,
                \Lunar\Models\DiscountPurchasable::class,
                'purchasable_id',
                'id',
                'id',
                'discount_id'
            )
            ->scopes(['active'])
            ->where('lunar_discount_purchasables.purchasable_type', \Lunar\Models\Product::class)
        ;
    }

    public function reviews(): HasMany
    {
        return $this
            ->hasMany(Review::class, 'reviewable_id')
            ->where('reviewable_type', \Lunar\Models\Product::class)
        ;
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function getAverageRatingAttribute(): ?float
    {
        return (float) ($this->reviews()->avg('rating') ?? 0);
    }

    public function collectionHierarchy(): \Illuminate\Support\Collection
    {
        $recursiveChildrenQuery = DB::table('lunar_collections', 'root')
            ->select(['root.*', DB::raw('1 as depth')])
            ->join('lunar_collection_product', 'root.id', '=', 'lunar_collection_product.collection_id')
            ->where('lunar_collection_product.product_id', '=', $this->id)
            ->unionAll(
                DB::table('lunar_collections', 'child')
                    ->select(['child.*', DB::raw('prev_level.depth + 1')])
                    ->join('recursive_hierarchy as prev_level', 'prev_level.parent_id', '=', 'child.id')
            )
        ;
        $hierarchyQuery = Collection::from('recursive_hierarchy')
            ->select('*')
            ->withRecursiveExpression('recursive_hierarchy', $recursiveChildrenQuery)
            ->orderBy('depth', 'desc')
        ;

        return $hierarchyQuery->get();
    }
}
