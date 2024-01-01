<?php

declare(strict_types=1);

namespace App\Models\Review;

use App\Models\CanBeArchived;
use App\Models\NeedsApproval;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
