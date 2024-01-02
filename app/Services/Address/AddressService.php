<?php

declare(strict_types=1);

namespace App\Services\Address;

use App\Models\Address;

class AddressService
{
    public function create(array $createAddressInput): Address
    {
        $user     = auth()->user();
        $customer = $user->retailCustomers()->first();
        $address  = new Address([
            'customer_id'           => $customer->id,
            'title'                 => $createAddressInput['title'],
            'first_name'            => $createAddressInput['firstName'],
            'last_name'             => $createAddressInput['lastName'],
            'company_name'          => $createAddressInput['companyName'],
            'line_one'              => $createAddressInput['lineOne'],
            'city'                  => $createAddressInput['city'],
            'postcode'              => $createAddressInput['postcode'],
            'country_id'            => $createAddressInput['countryId'],
            'delivery_instructions' => $createAddressInput['deliveryInstructions'],
            'contact_email'         => $createAddressInput['contactEmail'],
            'contact_phone'         => $createAddressInput['contactPhone'],
            'shipping_default'      => $createAddressInput['shippingDefault'],
            'billing_default'       => $createAddressInput['billingDefault'],
        ]);
        $address->save();

        return $address;
    }

    public function update(Address $address): Address
    {
        return $address;
    }

    public function delete(Address $address): void
    {
        $address->delete();
    }
}
