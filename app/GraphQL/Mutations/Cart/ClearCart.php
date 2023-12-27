<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Cart;

use App\Models\Cart;

final class ClearCart extends CartMutation
{
    public function __invoke(mixed $root, array $args): Cart
    {
        return $this->cartService->clearCart();
    }
}
