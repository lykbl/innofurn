<?php

declare(strict_types=1);

namespace App\Models\PromotionBanner;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Lunar\Base\BaseModel;
use Lunar\Base\Casts\AsAttributeData;
use Lunar\Base\Traits\HasAttributes;
use Lunar\Base\Traits\Searchable;
use Lunar\Models\Attribute;

final class PromotionBannerType extends BaseModel
{
    use Searchable;
    use HasAttributes;

    protected $table = 'promotion_banner_types';

    // TODO verfiy
    protected $guarded = [];

    protected $casts = [
        'style'          => PromotionBannerStyle::class,
        'attribute_data' => AsAttributeData::class,
    ];

    public function promotionBanners(): HasMany
    {
        return $this->hasMany(PromotionBanner::class, 'promotion_banner_type_id');
    }

    public function mappedAttributes(): MorphToMany
    {
        $prefix = config('lunar.database.table_prefix');

        return $this->morphToMany(
            Attribute::class,
            'attributable',
            "{$prefix}attributables"
        )->withTimestamps();
    }

    public function promotionBannerAttributes()
    {
        return $this->mappedAttributes()->whereAttributeType(PromotionBanner::class);
    }
}
