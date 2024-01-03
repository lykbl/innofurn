<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Checkout;

use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Stripe\PaymentIntent;

class CreatePaymentIntent extends CheckoutMutation
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): PaymentIntent
    {
        return $this->checkoutService->createPaymentIntent(...$args['input']);
    }
}
