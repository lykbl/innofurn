<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Checkout;

use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class createPaymentIntent extends CheckoutMutation
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $this->checkoutService->createPaymentIntent(...$args['input']);

        return true;
    }
}
