<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Cart;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class MyCart
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): void
    {
    }
}
