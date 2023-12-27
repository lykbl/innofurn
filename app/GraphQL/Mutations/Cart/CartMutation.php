<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Cart;

use App\Models\Cart;
use App\Services\Cart\CartService;

abstract class CartMutation
{
    public function __construct(protected readonly CartService $cartService)
    {
    }

    abstract public function __invoke(mixed $root, array $args): Cart;
}
