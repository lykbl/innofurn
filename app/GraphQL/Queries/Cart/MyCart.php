<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Cart;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class MyCart
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return Auth::guard(Arr::first(config('sanctum.guard', 'sanctum')))->user()?->activeCart;
    }
}
