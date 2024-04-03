<?php

declare(strict_types=1);

namespace App\Models\Review;

use App\Models\CanBeArchived;
use App\Models\Customer;
use App\Models\CustomerUserPivot;
use App\Models\NeedsApproval;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

/* @method static Builder withUnapproved() */
/* @method static Builder onlyApproved() */
/* @method static Builder withoutApproved() */
class Review extends Model
{
    use NeedsApproval;
    use CanBeArchived;
    use SoftDeletes;
    use Searchable;

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

    public function searchableAs()
    {
        return ['reviews_index_en', 'reviews_index_es'];
    }

    public function toSearchableArray()
    {
        return [
            'id'                 => $this->id,
            'title'              => $this->title,
            'body'               => $this->body,
            'product_id'         => $this->variant->product_id,
            'product_variant_id' => $this->product_variant_id,
            'user_id'            => $this->user_id,
            'rating'             => $this->rating,
            'created_at'         => $this->created_at,
        ];
    }
}
