<?php

declare(strict_types=1);

namespace App\Domains\ProductVariant;

use App\Models\Translatable;
use Lunar\Models\ProductVariant as BaseProductVariant;

class ProductVariant extends BaseProductVariant implements Translatable
{
}
