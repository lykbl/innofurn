<?php

declare(strict_types=1);

namespace App\Domains\Attributes;

use Illuminate\Database\Eloquent\Model;

class AggregatedIndexedAttributeValue extends Model
{
    protected $table = 'indexed_product_attribute_values';

    protected $casts = [
        'values' => 'array',
    ];

    protected $fillable = [
        'values',
        'type',
        'handle',
        'label',
    ];
}
