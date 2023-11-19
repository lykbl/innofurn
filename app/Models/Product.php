<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Lunar\Models\Product as BaseProduct;

class Product extends BaseProduct
{
    /**
     * Return the product variants relation.
     *
     * @return HasMany
     */
    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }
}
