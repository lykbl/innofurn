<?php

declare(strict_types=1);

namespace App\GraphQL\Subscriptions\ProductVariant;

use Illuminate\Http\Request;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Schema\Types\GraphQLSubscription;
use Nuwave\Lighthouse\Subscriptions\Subscriber;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class StockDecreased extends GraphQLSubscription
{
    public function authorize(Subscriber $subscriber, Request $request): bool
    {
        return true;
    }

    public function filter(Subscriber $subscriber, mixed $root): bool
    {
        return true;
    }

    public function resolve(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): mixed
    {
        return $root['record'];
    }
}
