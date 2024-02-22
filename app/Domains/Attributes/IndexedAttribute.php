<?php

declare(strict_types=1);

namespace App\Domains\Attributes;

use Illuminate\Database\Eloquent\Model;

class IndexedAttribute extends Model
{
    protected $table = 'indexed_product_attribute_values';

    public $timestamps   = false;
    public $incrementing = false;

    protected $casts = [
        'value' => 'json',
    ];

    protected $fillable = [
        'value',
        'attributable_id',
        'product_type_id',
        'language_code',
    ];

    public static function boot(): void
    {
        parent::boot();

        self::creating(function ($model): void {
            $model->id = md5($model->attributable_id.$model->product_type_id.$model->language_code.json_encode($model->value, JSON_THROW_ON_ERROR));
        });
    }
}
