<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Cart;

use Lunar\Models\Cart;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class ClearCartItem extends CartMutation
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Cart
    {
        return $this->cartService->clearCartItem($args['sku']);
    }
}
