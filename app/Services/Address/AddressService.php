<?php

declare(strict_types=1);

namespace App\Services\Address;

use App\Models\Address;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AddressService
{
    public function create(array $createAddressInput): Address
    {
        $customer = auth()->user()->retailCustomers()->first();
        $address  = new Address([
            'customer_id' => $customer->id,
            ...Arr::mapWithKeys($createAddressInput, fn ($value, $key) => [Str::snake($key) => $value]),
        ]);
        $address->save();

        return $address;
    }

    public function update(array $updateAddressInput): Address
    {
        if (true === $updateAddressInput['defaultShippingAddress']) {
        }

        $address = Address::find($updateAddressInput['id'])
            ->update(
                Arr::mapWithKeys($updateAddressInput, fn ($value, $key) => [Str::snake($key) => $value]))
        ;

        return $address;
    }

    public function delete(int $id): void
    {
        Address::find($id)->delete();
    }
}
