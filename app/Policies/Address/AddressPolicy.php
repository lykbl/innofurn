<?php

declare(strict_types=1);

namespace App\Policies\Address;

use App\Models\Address;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AddressPolicy
{
    public function add(User $user): Response
    {
        return $user->retailCustomer->addresses()->count() <= 6 ?
            Response::allow() :
            Response::deny('You can only have 6 addresses.');
    }

    public function edit(User $user, Address $address): Response
    {
        return $this->ownedByUser($user, $address)
            ? Response::allow()
            : Response::deny('Access denied.');
    }

    private function ownedByUser(User $user, Address $address): bool
    {
        return $user->retailCustomer->id === $address->customer_id;
    }
}
