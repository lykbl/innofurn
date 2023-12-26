<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Cart;

use App\Models\Cart;
use App\Services\Cart\CartService;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;

abstract class CartMutation
{
    public function __construct(protected readonly CartService $cartService)
    {
    }

    abstract public function __invoke(mixed $root, array $args): Cart;

    public function user(): Authenticatable
    {
        $user = auth()->user();
        if (null === $user) {
            throw new Exception('Authentication required.');
        }

        return $user;
    }
}
