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
            $this->addressBelongsToCustomer($user->retailCustomer->id, $args['billingAddressId'])
            && $this->addressBelongsToCustomer($user->retailCustomer->id, $args['shippingAddressId'])
            ? Response::allow()
            : Response::deny('Access denied.')
        ;
    }

    public function capturePaymentIntent(User $user, mixed $args): Response
    {
        // TODO fix
        return Response::allow();
    }

    private function addressBelongsToCustomer(int $customerId, int $addressId): bool
    {
        return Address::where(['customer_id' => $customerId, 'id' => $addressId])->exists();
    }
}
