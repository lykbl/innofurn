<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Lunar\Models\Customer;

class UserService
{
    public function signUp(string $firstName, string $lastName, string $password, string $email): User
    {
        /** @var User $user */
        $user = User::create([
            'name' => "$firstName $lastName",
            'password'   => $password,
            'email'      => $email,
        ]);
        $user->save();

        /** @var Customer $customer */
        $customer = Customer::create([
            'first_name' => $firstName,
            'last_name'  => $lastName,
        ]);
        $customer->save();
        $customer->users()->attach($user->id);

        event(new Registered($user));

        return $user;
    }
}