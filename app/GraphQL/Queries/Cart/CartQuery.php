<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Cart;

use App\GraphQL\ResolverInterface;
use App\Services\Cart\CartService;

abstract class CartQuery implements ResolverInterface
{
    public function __construct(
        protected CartService $cartService
    ) {
    }
}
