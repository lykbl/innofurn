<?php

declare(strict_types=1);

namespace App\Services\Checkout;

use App\Models\Address;
use Lunar\Facades\Payments;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Cart;
use Lunar\Stripe\Facades\StripeFacade;
use Stripe\PaymentIntent;

class CheckoutService
{
    public function createPaymentIntent(int $billingAddressId, int $shippingAddressId, int|string $shippingMethodId): PaymentIntent
    {
        // todo validate belongs to user
        $billingAddress  = Address::find($billingAddressId);
        $shippingAddress = Address::find($shippingAddressId);
        /** @var Cart $cart */
        $cart = auth()->user()->activeCart;
        $cart
            ->setShippingAddress($shippingAddress)
            ->setBillingAddress($billingAddress)
            ->setShippingOption(ShippingManifest::getOption($cart, $shippingMethodId));

        /** @var PaymentIntent $paymentIntent */
        $paymentIntent = StripeFacade::createIntent($cart);
        Payments::driver('stripe')
            ->withData(['payment_intent' => $paymentIntent->id])
            ->cart($cart);
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
