<?php

declare(strict_types=1);

namespace App\GraphQL\Types\PaymentIntent;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Stripe\PaymentIntent;

final class ClientSecret
{
    public function __invoke(PaymentIntent $paymentIntent, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): string
    {
        return $paymentIntent->client_secret;
    }
}
