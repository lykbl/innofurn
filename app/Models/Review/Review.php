<?php

declare(strict_types=1);

namespace App\Models\Review;

use App\Models\CanBeArchived;
use App\Models\Customer;
use App\Models\CustomerUserPivot;
use App\Models\NeedsApproval;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use NeedsApproval;
    use CanBeArchived;
    use SoftDeletes;

    protected $fillable = [
        'product_variant_id',
        'user_id',
        'title',
        'body',
        'rating',
    ];

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

    public function customer(): HasOneThrough
    {
        return $this->hasOneThrough(
            Customer::class,
            CustomerUserPivot::class,
            'user_id',
            'id',
            'user_id',
            'customer_id'
        );
    }
}
