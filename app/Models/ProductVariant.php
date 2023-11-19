<?php

declare(strict_types=1);

namespace App\Models;

use Lunar\Models\ProductVariant as BaseProductVariant;

class ProductVariant extends BaseProductVariant
{
    //    /**
    //     * @return BelongsToMany
    //     */
    //    public function images(): BelongsToMany
    //    {
    //        $prefix = config('lunar.database.table_prefix');
    //
    //        return $this->belongsToMany(config('media-library.media_model'), "{$prefix}media_product_variant")
    //            ->withPivot(['primary', 'position'])
    //            ->orderBy('position')
    //            ->withTimestamps();
    //    }
}
