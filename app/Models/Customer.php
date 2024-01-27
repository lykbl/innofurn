<?php

declare(strict_types=1);

namespace App\Models;

use App\Domains\Chat\Models\ChatRoom;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Lunar\Models\Customer as BaseCustomer;

class Customer extends BaseCustomer
{
    public function getNameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function defaultShippingAddress(): Address
    {
        return $this->addresses()->where('shipping_default', true)->first();
    }

    public function defaultBillingAddress(): Address
    {
        return $this->addresses()->where('billing_default', true)->first();
    }

    public function activeCart(): HasOne
    {
        return $this->hasOne(Cart::class, 'customer_id', 'id')->whereNull('completed_at');
    }

    public function activeChatRoom(): HasOne
    {
        return $this->hasOne(ChatRoom::class);
    }

    public function user(): HasOneThrough
    {
        return $this
            ->hasOneThrough(
                User::class,
                CustomerUserPivot::class,
                'customer_id',
                'id',
                'id',
                'user_id'
            )
        ;
    }
}
