<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Order;

use App\GraphQL\ResolverInterface;
use App\Models\Orders\Order;
use Illuminate\Database\Eloquent\Collection;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class MyOrders implements ResolverInterface
{
    /**
     * @param mixed          $root
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $resolveInfo
     *
     * @return Collection<Order>
     */
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Collection
    {
        return $context->user()->retailCustomer->orders; // TODO do this on schema level?
    }
}
