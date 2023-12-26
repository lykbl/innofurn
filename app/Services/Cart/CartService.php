<?php

declare(strict_types=1);

namespace App\Services\Cart;

use App\Models\Cart;
use App\Models\ProductVariant;
use App\Models\User;

class CartService
{
    public function addItem(User $user, int $productVariantId, int $quantity): Cart
    {
        $cart = $user->cart;
        /** @var Cart $cart */
        $productCartLine = $cart->lines()->where('purchasable_id', $productVariantId)->first();
        // TODO add default tax zone migration
        // TODO pass cart line id instead?
        if ($productCartLine) {
            $cart->updateLine($productCartLine->id, $productCartLine->quantity + $quantity); // use helper?
        } else {
            $cart->lines()->create([
                'cart_id'          => $cart->id,
                'purchasable_type' => ProductVariant::class,
                'purchasable_id'   => $productVariantId,
                'quantity'         => $quantity,
            ]);
        }

        return $cart;
    }

    public function removeItem(User $user, int $productVariantId, int $adjustment): Cart
    {
        $cart = $user->cart;
        /** @var Cart $cart */
        $productCartLine = $cart->lines()->where('purchasable_id', $productVariantId)->first();
        if ($productCartLine && $productCartLine->quantity - $adjustment > 0) {
            $cart->updateLine($productCartLine->id, $productCartLine->quantity - $adjustment);
        } else {
            $cart->remove($productCartLine->id);
        }

        return $cart;
    }

    public function clearCartItem(User $user, int $productVariantId): Cart
    {
        $cart = $user->cart;
        /** @var Cart $cart */
        $productCartLine = $cart->lines()->where('purchasable_id', $productVariantId)->first();
        if ($productCartLine) {
            $cart->remove($productCartLine->id);
        }

        return $cart;
    }

    public function clearCart(User $user): Cart
    {
        return $user->cart->clear();
    }
}
