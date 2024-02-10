<?php

declare(strict_types=1);

namespace App\Domains\ProductVariant;

use App\Models\Review\Review;
use App\Models\Translatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Lunar\Base\Casts\AsAttributeData;
use Lunar\Base\Traits\HasAttributes;
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
}
