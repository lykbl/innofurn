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
    public function addItem(int $productVariantId, int $quantity): Cart
    {
        $cart = $this->getCart();
        /** @var CartSessionManager $cartManager */
        // TODO add default tax zone migration
        $purchasable = ProductVariant::find($productVariantId);
        AddOrUpdatePurchasable::run($cart, $purchasable, $quantity);

        return $cart->refresh();
    }

    public function removeItem(int $productVariantId, int $adjustment): Cart
    {
        $cart = $this->getCart();
        if (!$cartLine = $this->getCartLine($cart, $productVariantId)) {
            return $cart;
        }

        $cartLine->quantity - $adjustment > 0 ?
            UpdateCartLine::run($cartLine->id, $cartLine->quantity - $adjustment) :
            RemovePurchasable::run($cart, $cartLine->id);

        return $cart->refresh();
    }

    public function clearCartItem(int $productVariantId): Cart
    {
        $cart = $this->getCart();
        if (!$cartLine = $this->getCartLine($cart, $productVariantId)) {
            return $cart;
        }
        RemovePurchasable::run($cart, $cartLine->id);

        return $cart->refresh();
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
            'customer_id' => $user->retailCustomers()->first()->id,
        ]);
        $cart->save();

        return $cart;
    }

    private function getCartLine(Cart $cart, int $purchasableId): ?CartLine
    {
        $purchasableId = ProductVariant::find($purchasableId);

        return GetExistingCartLine::run($cart, $purchasableId);
    }
}
