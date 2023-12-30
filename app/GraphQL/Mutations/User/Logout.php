<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class Logout extends UserMutation
{
    /**
     * @param mixed          $root
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $resolveInfo
     *
     * @return Authenticatable
     */
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Authenticatable
    {
        $guard = Auth::guard(Arr::first(config('sanctum.guard', 'web')));
        $user  = $guard->user();
        $guard->logout();

        return $user;
    }
}
