<?php

declare(strict_types=1);

namespace App\Domains\ProductVariant;

use App\Models\Translatable;
use Lunar\Base\Traits\HasAttributes;
use Lunar\Models\ProductVariant as BaseProductVariant;

class ProductVariant extends BaseProductVariant implements Translatable
{
    use HasAttributes;
}
