<?php

declare(strict_types=1);

namespace App\Domains\ProductVariant;

use App\Models\Review\Review;
use App\Models\Translatable;
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
        return $this->hasMany(Review::class)->where('reviewable_type', self::class);
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
            ->where('lunar_discount_purchasables.purchasable_type', \Lunar\Models\ProductVariant::class)
        ;
    }
}
