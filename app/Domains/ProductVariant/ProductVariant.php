<?php

declare(strict_types=1);

namespace App\Domains\ProductVariant;

use App\Models\Review\Review;
use App\Models\Translatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Lunar\Base\Casts\AsAttributeData;
use Lunar\Base\Traits\HasAttributes;
use Lunar\Models\Discount;
use Lunar\Models\ProductVariant as BaseProductVariant;

class ProductVariant extends BaseProductVariant implements Translatable
{
    use HasAttributes;

    protected $casts = [
        'attribute_data' => AsAttributeData::class,
    ];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function getAverageRatingAttribute(): ?float
    {
        return (float) ($this->reviews()->avg('rating') ?? 0);
    }

    // TODO Is this a bug?
    public function getAttributableClassnameAttribute()
    {
        return \Lunar\Models\ProductVariant::class;
    }

    // TODO Add actual logic
    public function getIsFeaturedAttribute()
    {
        return $this->id <= 2;
    }

    // TODO Add actual logic
    public function getRatingAttribute()
    {
        return [
            'average' => 3,
            'count'   => 500,
        ];
    }

    // TODO Add actual logic
    public function getIsFavoriteAttribute()
    {
        return $this->id > 2;
    }

    public function getActiveDiscountsAttribute()
    {
        $productVariantPivots = DB::query()
            ->select([
                'lunar_products.id as product_id',
                'lunar_product_variants.id as product_variant_id',
                'lunar_collection_product.collection_id as collection_id',
            ])
            ->from('lunar_product_variants')
            ->join('lunar_products', 'lunar_product_variants.product_id', '=', 'lunar_products.id')
            ->join('lunar_collection_product', 'lunar_products.id', '=', 'lunar_collection_product.product_id')
            ->where('lunar_product_variants.id', '=', $this->id)
        ;
        $collectionHierarchy = DB::query()
            ->select([
                'id',
                'parent_id as ord',
                'product_variant_pivots.collection_id as root_id',
            ])
            ->from('lunar_collections')
            ->join('product_variant_pivots', 'lunar_collections.id', '=', 'product_variant_pivots.collection_id')
            ->unionAll(
                DB::query()->select([
                    'c.id',
                    'c.parent_id',
                    'root_id',
                ])
                    ->from('lunar_collections as c')
                    ->join('collection_hierarchy as t', 'c.parent_id', 't.id')
            )
        ;
        $collections = DB::query()
            ->select([
                DB::raw('GROUP_CONCAT(id) as collections'),
                'root_id',
            ])
            ->from('collection_hierarchy')
            ->groupBy('root_id')
        ;
        $discountPivots = DB::query()
            ->select('*')
            ->withExpression('product_variant_pivots', $productVariantPivots)
            ->withRecursiveExpression('collection_hierarchy', $collectionHierarchy)
            ->withRecursiveExpression('collections', $collections)
            ->from('product_variant_pivots as pvp')
            ->leftJoin('collections', 'collections.root_id', '=', 'pvp.collection_id')
        ;

        $discountsQuery = Discount::query()
            ->select('lunar_discounts.*')
            ->withExpression('discount_pivots', $discountPivots)
            ->join('lunar_discount_purchasables', 'lunar_discounts.id', '=', 'lunar_discount_purchasables.discount_id')
            ->whereRaw('
                lunar_discount_purchasables.purchasable_id in (select product_variant_id from discount_pivots) or
                lunar_discount_purchasables.purchasable_id in (select product_id from discount_pivots)
            ')
            ->union(
                Discount::query()
                    ->select('lunar_discounts.*')
                    ->join('lunar_collection_discount', 'lunar_discounts.id', '=', 'lunar_collection_discount.discount_id')
                    ->whereRaw('lunar_collection_discount.collection_id in (select collections from discount_pivots)')
            );

        return $discountsQuery->get();
    }
}
