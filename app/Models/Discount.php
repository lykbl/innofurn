<?php

declare(strict_types=1);

namespace App\Models;

use Lunar\Base\Traits\Searchable;
use Lunar\Models\Discount as BaseDiscount;

class Discount extends BaseDiscount
{
    use Searchable;
}
