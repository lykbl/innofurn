<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Cart;

use App\GraphQL\ResolverInterface;
use App\Services\Cart\CartService;
use Lunar\Models\Cart;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

abstract class CartMutation implements ResolverInterface
{
    public function __construct(protected readonly CartService $cartService)
    {
    }

    abstract public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Cart;
}
