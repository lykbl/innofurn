<?php

declare(strict_types=1);

namespace App\Models;

use App\Domains\Chat\Models\ChatRoom;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Lunar\Models\Customer as BaseCustomer;

class Customer extends BaseCustomer
{
    public function defaultShippingAddress(): Address
    {
        return $this->addresses()->where('shipping_default', true)->first();
    }

    public function defaultBillingAddress(): Address
    {
        return $this->addresses()->where('billing_default', true)->first();
    }

    public function activeChatRoom(): HasOne
    {
        return $this->hasOne(ChatRoom::class);
    }

    public function getNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }
}
