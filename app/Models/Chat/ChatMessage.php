<?php

declare(strict_types=1);

namespace App\Models\Chat;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lunar\Hub\Models\Staff;

class ChatMessage extends Model
{
    protected $table = 'chat_messages';

    protected $fillable = [
        'body',
        'chat_room_id',
        'customer_id',
        'staff_id',
        'status',
    ];

    public function chatRoom(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}
