<?php

declare(strict_types=1);

namespace App\Models\Review;

use App\Models\CanBeArchived;
use App\Models\NeedsApproval;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use NeedsApproval;
    use CanBeArchived;

    protected $fillable = [
        'product_variant_id',
        'user_id',
        'title',
        'body',
        'rating',
        'approved_at',
        'archived_at',
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
