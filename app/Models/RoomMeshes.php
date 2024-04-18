<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomMesh extends Model
{
    protected $fillable = [
        'room_id',
        'position',
        'rotation',
        'material',
        'geometry',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
