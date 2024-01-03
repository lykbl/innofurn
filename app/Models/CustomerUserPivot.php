<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CustomerUserPivot extends Pivot
{
    protected $table = 'lunar_customer_user';
}
