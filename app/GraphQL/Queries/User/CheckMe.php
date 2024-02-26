<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\User;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CheckMe extends UserQuery
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ?Authenticatable
    {
        return Auth::guard('web')->user();
    }
}
