<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Lunar\Models\ProductVariant as BaseProductVariant;

class ProductVariant extends BaseProductVariant
{
    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    /**
     * @return BelongsToMany
     */
    public function images(): BelongsToMany
    {
        $prefix = config('lunar.database.table_prefix');

        return $this->belongsToMany(config('media-library.media_model'), "{$prefix}media_product_variant")
            ->withPivot(['primary', 'position'])
            ->orderBy('position')
            ->withTimestamps();
    }
}
