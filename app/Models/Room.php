<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'product_variant_id',
        'title',
        'camera_position',
        'look_at',
        'glb_location',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function meshes(): HasMany
    {
        return $this->hasMany(RoomMesh::class);
    }
}
