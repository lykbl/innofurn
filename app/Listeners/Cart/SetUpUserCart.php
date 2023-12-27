<?php

declare(strict_types=1);

namespace App\Listeners\Cart;

use App\Models\Cart;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Lunar\Facades\CartSession;

class SetUpUserCart
{
    /**
     * Handle the event.
     *
     * @param Registered $event
     *
     * @return void
     *
     * @throws Exception
     */
    public function handle(Registered $event): void
    {
        /* @var User $user */
        //        $user = $event->user;
        //        /** @var Cart $cart */
        //        $cart = CartSession::current();
        //        $cart->associate($user);
        //        $cart->setCustomer($user->retailCustomers()->first());
    }
}
