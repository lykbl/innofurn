<?php

declare(strict_types=1);

namespace App\Services\Address;

use App\Models\Address;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AddressService
{
    public function create(array $addressInput): Address
    {
        $customer = auth()->user()->retailCustomer;
        $this->resetDefaults($customer->id, $addressInput['shippingDefault'], $addressInput['billingDefault']);
        $address = new Address([
            'customer_id' => $customer->id,
            ...Arr::mapWithKeys($addressInput, fn ($value, $key) => [Str::snake($key) => $value]),
        ]);
        $address->save();

        return $address;
    }

    public function update(array $addressInput): Address
    {
        $customer = auth()->user()->retailCustomer;
        $this->resetDefaults($customer->id, $addressInput['shippingDefault'], $addressInput['billingDefault']);
        $address = Address::find($addressInput['id']);
        $address
            ->update(
                Arr::mapWithKeys($addressInput, fn ($value, $key) => [Str::snake($key) => $value])
            )
        ;

        return $address->refresh();
    }

    public function delete(int $id): bool
    {
        return Address::find($id)->delete();
    }

    private function resetDefaults(int $customerId, bool $shipping, bool $billing): void
    {
        if ($shipping) {
            $column = 'shipping_default';
            Address::where(['customer_id' => $customerId])->update([$column => false]);
        }

        if ($billing) {
            $column = 'billing_default';
            Address::where(['customer_id' => $customerId])->update([$column => false]);
        }
    }
}
