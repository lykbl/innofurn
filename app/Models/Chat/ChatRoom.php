<?php

declare(strict_types=1);

namespace App\Models\Chat;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lunar\Base\Traits\Searchable;

class ChatRoom extends Model
{
    use SoftDeletes;
    use Searchable;

    protected $table = 'chat_rooms';

    protected $fillable = [
        'customer_id',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
