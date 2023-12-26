<?php

declare(strict_types=1);

namespace App\Listeners\Cart;

use Illuminate\Auth\Events\Registered;

class SetUpUserCart
{
    /**
     * Handle the event.
     *
     * @param Registered $event
     *
     * @return void
     */
    public function handle(Registered $event): void
    {
        /** @var \App\Models\User $user */
        $user = $event->user;
        // TODO hydrate on sign up

        $cart = new \App\Models\Cart([
            'currency_id' => \App\Models\Currency::getDefault()->id,
            'channel_id'  => \App\Models\Channel::getDefault()->id,
        ]);

        $cart->save();
        $cart->associate($user);
        $cart->setCustomer($user->retailCustomers()->first());
    }
}
