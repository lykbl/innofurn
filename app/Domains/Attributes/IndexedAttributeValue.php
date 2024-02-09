<?php

declare(strict_types=1);

namespace App\Domains\Attributes;

use Illuminate\Database\Eloquent\Model;

class IndexedAttributeValue extends Model
{
    protected $table = 'indexed_product_attribute_values';

    public $timestamps = false;

    protected $fillable = [
        'value',
        'type',
        'attributable_id',
        'product_type_id',
        'language_code',
    ];
}
