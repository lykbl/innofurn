<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Lunar\Models\Address as LunarAddress;

class Address extends LunarAddress
{
    public function user(): HasOneThrough
    {
        return $this
            ->hasOneThrough(
                User::class,
                CustomerUserPivot::class,
                'customer_id',
                'id',
                'id',
                'user_id'
            )
        ;
    }
}
