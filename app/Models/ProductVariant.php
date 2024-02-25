<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Review\Review;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        return $this
            ->hasMany(Review::class, 'reviewable_id', 'id')
            ->where('reviewable_type', \Lunar\Models\ProductVariant::class)
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
    public function getIsFavoriteAttribute()
    {
        return $this->id > 2;
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
            ->where('lunar_discount_purchasables.purchasable_type', \Lunar\Models\ProductVariant::class)
        ;
    }
}
