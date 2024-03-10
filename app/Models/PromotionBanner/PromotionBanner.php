<?php

declare(strict_types=1);

namespace App\Models\PromotionBanner;

use App\Models\Discount;
use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lunar\Base\BaseModel;
use Lunar\Base\Casts\AsAttributeData;
use Lunar\Base\Traits\HasAttributes;
use Lunar\Base\Traits\HasMedia;
use Lunar\Base\Traits\HasTranslations;
use Lunar\Base\Traits\HasUrls;
use Lunar\Base\Traits\LogsActivity;
use Lunar\Base\Traits\Searchable;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

final class PromotionBanner extends BaseModel implements SpatieHasMedia
{
    use Searchable;
    use SoftDeletes;
    use HasAttributes;
    use HasUrls;
    use HasMedia;
    use LogsActivity;
    use HasTranslations;

    protected $table = 'promotion_banners';

    // TODO verfiy
    protected $guarded = [];

    protected $casts = [
        'style'          => PromotionBannerStyle::class,
        'attribute_data' => AsAttributeData::class,
    ];

    public function promotionBannerType(): BelongsTo
    {
        return $this->belongsTo(PromotionBannerType::class);
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function images()
    {
        $prefix = config('lunar.database.table_prefix');

        return $this->belongsToMany(\Spatie\MediaLibrary\MediaCollections\Models\Media::class, "{$prefix}media_promotion_banner")
            ->withPivot(['primary', 'preview'])
            ->orderBy('primary')
            ->withTimestamps()
        ;
    }

    public function primaryImage(): HasOneThrough
    {
        return $this->hasOneThrough(
            Media::class,
            PromotionBannerMedia::class,
            'promotion_banner_id',
            'id',
            'id',
            'media_id'
        )->where('lunar_media_promotion_banner.primary', '=', true);
    }

    public function bannerImage(): HasOneThrough
    {
        return $this->hasOneThrough(
            Media::class,
            PromotionBannerMedia::class,
            'promotion_banner_id',
            'id',
            'id',
            'media_id'
        )->where('lunar_media_promotion_banner.banner', '=', true);
    }
}
