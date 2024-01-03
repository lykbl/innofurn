<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Checkout;

use App\GraphQL\ResolverInterface;
use App\Services\Checkout\CheckoutService;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

abstract class CheckoutMutation implements ResolverInterface
{
    public function __construct(protected readonly CheckoutService $checkoutService)
    {
    }

    abstract public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo);
}
