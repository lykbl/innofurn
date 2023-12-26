<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Cart;

use App\Models\Cart;
use Exception;

final class RemoveItem extends CartMutation
{
    /**
     * @param array{productVariantId: int, quantity: int} $args
     *
     * @throws Exception
     */
    public function __invoke(mixed $root, array $args): Cart
    {
        return $this->cartService->removeItem($this->user(), (int) $args['productVariantId'], $args['quantity']);
    }
}
