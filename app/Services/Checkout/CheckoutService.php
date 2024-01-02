<?php

declare(strict_types=1);

namespace App\Services\Checkout;

use App\Models\Address;
use Lunar\Facades\Payments;
use Lunar\Facades\ShippingManifest;
use Lunar\Models\Cart;
use Lunar\Stripe\Facades\StripeFacade;
use Lunar\Stripe\StripePaymentType;
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
        $cart->setShippingAddress($shippingAddress);
        $cart->setBillingAddress($billingAddress);
        $cart->setShippingOption(ShippingManifest::getOption($cart, $shippingMethodId));
        /** @var StripePaymentType $stripePayment */
        $stripePayment = Payments::driver('stripe');
        /** @var PaymentIntent $paymentIntent */
        $paymentIntent = StripeFacade::createIntent($cart);
        $stripePayment->withData([
            'payment_intent' => $paymentIntent->id,
        ]);
        $stripePayment->cart($cart);
        $cart->createOrder();

        return $paymentIntent;
    }

    public function captureIntent(string $paymentIntentId)
    {
        /** @var Cart $cart */
        $cart = auth()->user()->activeCart;
        /** @var StripePaymentType $stripePayment */
        $stripePayment = Payments::driver('stripe');
        $stripePayment->cart($cart);
        /* @var PaymentIntent $paymentIntent */
        $stripePayment->withData([
            'payment_intent' => $paymentIntentId,
        ]);
        $stripePayment->authorize();
        $cart->completed_at = now();
        $cart->save();

        return true;
    }
}
