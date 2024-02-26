<?php

declare(strict_types=1);

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\Channel;
use App\Models\Currency;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Lunar\Actions\Carts\AddOrUpdatePurchasable;
use Lunar\Actions\Carts\GetExistingCartLine;
use Lunar\Actions\Carts\RemovePurchasable;
use Lunar\Actions\Carts\UpdateCartLine;
use Lunar\Facades\CartSession;
use Lunar\Managers\CartSessionManager;
use Lunar\Models\CartLine;

class CartService
{
    public function addItem(string $sku, int $quantity): Cart
    {
        $cart = $this->getCart();
        /** @var CartSessionManager $cartManager */
        $purchasable = ProductVariant::where('sku', '=', $sku)->first();
        AddOrUpdatePurchasable::run($cart, $purchasable, $quantity);

        return $cart->calculate();
    }

    public function removeItem(string $sku, int $adjustment): Cart
    {
        $cart = $this->getCart();
        if (!$cartLine = $this->getCartLine($cart, $sku)) {
            return $cart;
        }

        $cartLine->quantity - $adjustment > 0 ?
            UpdateCartLine::run($cartLine->id, $cartLine->quantity - $adjustment) :
            RemovePurchasable::run($cart, $cartLine->id);

        return $cart->calculate();
    }

    public function clearCartItem(string $sku): Cart
    {
        $cart = $this->getCart();
        if (!$cartLine = $this->getCartLine($cart, $sku)) {
            return $cart;
        }
        RemovePurchasable::run($cart, $cartLine->id);

        return $cart->calculate();
    }

    public function clearCart(): Cart
    {
        return $this->getCart()->clear()->refresh();
    }

    private function getCart(): Cart
    {
        $user = Auth::user();
        if (!$user) {
            return CartSession::current();
        }

        return $user->activeCart ?? $this->createCartForUser($user);
    }

    private function createCartForUser(User $user): Cart
    {
        $cart = Cart::create([
            'currency_id' => Currency::getDefault()->id,
            'channel_id'  => Channel::getDefault()->id,
            'user_id'     => $user->id,
            'customer_id' => $user->retailCustomer->id,
        ]);
        $cart->save();

        return $cart;
    }

    private function getCartLine(Cart $cart, string $sku): ?CartLine
    {
        $purchasable = ProductVariant::where('sku', '=', $sku)->first();

        return GetExistingCartLine::run($cart, $purchasable);
    }
}
