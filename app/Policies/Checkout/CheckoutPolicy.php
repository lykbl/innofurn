<?php

declare(strict_types=1);

namespace App\Policies\Checkout;

use App\Models\Address;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CheckoutPolicy
{
    public function createPaymentIntent(User $user, mixed $args): Response
    {
        return
            $this->addressBelongsToCustomer($user->retailCustomer->id, $args['input']['billingAddressId'])
            && $this->addressBelongsToCustomer($user->retailCustomer->id, $args['input']['shippingAddressId'])
            ? Response::allow()
            : Response::deny('Access denied.')
        ;
    }

    private function addressBelongsToCustomer(int $customerId, int $addressId): bool
    {
        return Address::where(['customer_id' => $customerId, 'id' => $addressId])->exists();
    }
}
