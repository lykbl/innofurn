<?php

declare(strict_types=1);

namespace App\Domains\ProductVariant;

use App\Models\Translatable;
use Lunar\Base\Traits\HasAttributes;
use Lunar\Models\ProductVariant as BaseProductVariant;

class ProductVariant extends BaseProductVariant implements Translatable
{
    use HasAttributes;

    // TODO Is this a bug?
    public function getAttributableClassnameAttribute()
    {
        return \Lunar\Models\ProductVariant::class;
    }

    // TODO Add actual logic
    public function getIsFeaturedAttribute()
    {
        return (bool) random_int(0, 1);
    }

    // TODO Add actual logic
    public function getRatingAttribute()
    {
        return [
            'average' => random_int(1, 5),
            'count'   => random_int(1, 9999),
        ];
    }

    // TODO Add actual logic
    public function getIsFavoriteAttribute()
    {
        return (bool) random_int(0, 1);
    }
}
