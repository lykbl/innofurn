<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Review\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use Lunar\Models\Discount;
use Lunar\Models\Product as BaseProduct;
use Lunar\Models\ProductVariant as LunarProductVariant;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/** @method Builder withSlug */
class Product extends BaseProduct implements Translatable
{
    use HasRelationships;

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
            ->where('lunar_discount_purchasables.purchasable_type', BaseProduct::class)
        ;
    }

    public function reviews(): HasManyThrough
    {
        $reviews = $this
            ->hasManyThrough(
                Review::class,
                ProductVariant::class,
                'product_id',
                'product_variant_id',
                'id',
                'id'
            )
        ;

        return $reviews;
    }

    // TODO replace with ORM?
    // TODO add eager loading?
    public function getReviewsBreakdownAttribute(): array
    {
        return DB::query()
            ->from('reviews')
            ->select([
                'reviews.rating',
                DB::raw('count(*) as count'),
            ])
            ->join('lunar_product_variants', 'reviews.product_variant_id', '=', 'lunar_product_variants.id')
            ->join('lunar_products', 'lunar_product_variants.product_id', '=', 'lunar_products.id')
            ->whereNull('lunar_products.deleted_at')
            ->whereNotNull('reviews.approved_at')
            ->where('lunar_products.id', $this->id)
            ->groupBy('reviews.rating')
            ->orderBy('reviews.rating', 'desc')
            ->get()
            ->toArray()
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

    public function getVariantsCountAttribute(): int
    {
        return $this->variants()->count();
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

    //    public function options(): HasManyDeep
    //    {
    //        $options = $this->hasManyDeep(
    //            ProductOption::class,
    //            [ProductVariant::class, ProductOptionValueProductVariant::class, ProductOptionValue::class],
    //            ['product_id', 'variant_id', 'product_option_id', 'id'],
    //            ['id', 'id', 'value_id', 'product_option_id'],
    //        )
    //            ->select('lunar_product_options.*')
    //            ->distinct();
    //
    //        return $options;
    //    }

    public static function withSlug(string $slug)
    {
        return Product::query()->withWhereHas('urls', fn ($query) => $query->where('slug', $slug));
    }

    public function searchableAs()
    {
        return 'products';
    }

    public function toSearchableArray()
    {
        // TODO index this right
        return [
            'id'   => $this->id,
            'name' => $this->translateAttribute('name'),
       ];
    }

    public function primaryImage(): HasOneThrough
    {
        return $this->hasOneThrough(
            \Spatie\MediaLibrary\MediaCollections\Models\Media::class,
            MediaProductVariant::class,
            'product_variant_id',
            'id',
            'id',
            'media_id',
        )->where('lunar_media_product_variant.primary', true);
    }

    public function startingPrice(): HasOneThrough
    {
        $relation = $this->hasOneThrough(
            Price::class,
            ProductVariant::class,
            'product_id',
            'priceable_id',
            'id',
            'id',
        )
            ->where('lunar_prices.priceable_type', LunarProductVariant::class)
            ->orderBy('price', 'asc')
        ;

        return $relation;
    }
}
