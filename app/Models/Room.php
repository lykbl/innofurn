<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lunar\Base\BaseModel;
use Lunar\Base\Casts\AsAttributeData;
use Lunar\Base\Traits\HasAttributes;
use Lunar\Base\Traits\HasUrls;

class Room extends BaseModel
{
    use SoftDeletes;
    use HasUrls;
    use HasAttributes;

    protected $fillable = [
        'product_variant_id',
        'camera_position',
        'look_at',
        'glb_location',
        'active',
        'attribute_data',
    ];

    protected $casts = [
        'active' => 'boolean',
        'attribute_data' => AsAttributeData::class,
    ];

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function meshes(): HasMany
    {
        return $this->hasMany(RoomMesh::class);
    }

//    public function mappedAttributes(): MorphToMany
//    {
//        $prefix = config('lunar.database.table_prefix');
//
//        return $this->morphToMany(
//            Attribute::class,
//            'attributable',
//            "{$prefix}attributables"
//        )->withTimestamps();
//    }
}
