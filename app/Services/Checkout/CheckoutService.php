<?php

declare(strict_types=1);

namespace App\Services\Checkout;

use App\Models\Address;
use App\Models\Customer;
use Lunar\Facades\Payments;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Cart;
use Lunar\Stripe\Facades\StripeFacade;
use Stripe\PaymentIntent;

class CheckoutService
{
    public function createPaymentIntent(?int $billingAddressId, ?int $shippingAddressId, string $shippingMethodId): PaymentIntent
    {
        /** @var Cart $cart */
        $cart = auth()->user()->activeCart;
        /** @var Customer $customer */
        $customer        = auth()->user()->retailCustomer;
        $billingAddress  = $billingAddressId ? Address::find($billingAddressId) : $customer->defaultBillingAddress();
        $shippingAddress = $shippingAddressId ? Address::find($shippingAddressId) : $customer->defaultShippingAddress();
        $cart
            ->setShippingAddress($shippingAddress)
            ->setBillingAddress($billingAddress)
            ->setShippingOption(ShippingManifest::getOption($cart, $shippingMethodId));

        /** @var PaymentIntent $paymentIntent */
        $paymentIntent = StripeFacade::createIntent($cart);
        $cart->createOrder();

        return $paymentIntent;
    }

    public function captureIntent(string $paymentIntentId): bool
    {
        /** @var Cart $cart */
        $cart = auth()->user()->activeCart;
        Payments::driver('stripe')
            ->cart($cart)
            ->withData(['payment_intent' => $paymentIntentId])
            ->authorize();
        $cart->update(['completed_at' => now()]);

        return true;
    }
}
