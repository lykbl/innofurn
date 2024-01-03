<?php

declare(strict_types=1);

namespace App\Models;

class CustomerUserPivot extends \Illuminate\Database\Eloquent\Relations\Pivot
{
    protected $table = 'lunar_customer_user';
}
