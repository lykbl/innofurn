<?php

declare(strict_types=1);

namespace App\Providers;

use Lunar\Base\ShippingModifier;
use Lunar\DataTypes\Price as PriceDataType;
use Lunar\DataTypes\ShippingOption;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Cart;
use Lunar\Models\TaxClass;

enum ShippingOptionIdentifier: string
{
    case BASDEL = 'BASDEL';
    case EXPDEL = 'EXPDEL';
    case PICKUP = 'PICKUP';
}

class ShippingOptionsProvider extends ShippingModifier
{
    public function handle(Cart $cart): void
    {
        $taxClass = TaxClass::first();

        ShippingManifest::addOptions(collect([
            new ShippingOption(
                name: 'Basic Delivery',
                description: 'A basic delivery option',
                identifier: ShippingOptionIdentifier::BASDEL->value,
                price: new PriceDataType(500, $cart->currency, 1),
                taxClass: $taxClass
            ),
            new ShippingOption(
                name: 'Express Delivery',
                description: 'An express delivery option',
                identifier: ShippingOptionIdentifier::EXPDEL->value,
                price: new PriceDataType(750, $cart->currency, 1), // TODO make this dynamic or something
                taxClass: $taxClass
            ),
            new ShippingOption(
                name: 'Pick up in store',
                description: 'Pick your order up in store',
                identifier: ShippingOptionIdentifier::PICKUP->value,
                price: new PriceDataType(0, $cart->currency, 1),
                taxClass: $taxClass,
                // This is for your reference, so you can check if a collection option has been selected.
                collect: true
            ),
        ]));
    }
}
