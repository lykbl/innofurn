<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Cart;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class MyCart extends CartQuery
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->cartService->getCart($context->user());
    }
}
