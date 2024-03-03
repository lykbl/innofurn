<?php

declare(strict_types=1);

namespace App\Services\Cart;

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
use Lunar\Models\Cart;

class CartService
{
    public function addOrUpdatePurchasable(string $sku, int $quantity): Cart
    {
        $cart = $this->getCart();
        $purchasable = ProductVariant::where('sku', '=', $sku)->first();
        app(config('lunar.cart.actions.add_to_cart', AddOrUpdatePurchasable::class))->execute(
            cart: $cart,
            purchasable: $purchasable,
            quantity: $quantity,
            meta: [],
        );

        return $cart->calculate();
    }

    public function updatePurchasable(string $sku, int $quantity): Cart
    {
        $cart = $this->getCart();
        /** @var GetExistingCartLine $getLineAction */
        $getLineAction = app(config('lunar.cart.actions.get_existing_cart_line', GetExistingCartLine::class));
        $cartLine = $getLineAction->execute(
            cart: $cart,
            purchasable: ProductVariant::where('sku', '=', $sku)->first(),
        );
        if (!$cartLine) {
            return $cart;
        }

        /** @var UpdateCartLine $updateAction */
        $updateAction = app(config('lunar.cart.actions.update_cart_line', UpdateCartLine::class));
        $updateAction->execute(
            cartLineId: $cartLine->id,
            quantity: $quantity,
        );

        return $cart->calculate();
    }

    public function clearCartItem(string $sku): Cart
    {
        $cart     = $this->getCart();
        $cartLine = app(config('lunar.cart.actions.get_existing_cart_line', GetExistingCartLine::class))->execute(
            cart: $cart,
            purchasable: ProductVariant::where('sku', '=', $sku)->first(),
            meta: []
        );
        app(config('lunar.cart.actions.remove_from_cart', RemovePurchasable::class))->execute(
            $cart,
            $cartLine->id,
        );

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
}
