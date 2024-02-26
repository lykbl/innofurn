<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use App\GraphQL\Exceptions\User\AuthenticationException;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class Login extends UserMutation
{
    /**
     * @param mixed          $root
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $resolveInfo
     *
     * @return Authenticatable
     *
     * @throws AuthenticationException
     */
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Authenticatable
    {
        $guard = Auth::guard(Arr::first(config('sanctum.guard', 'sanctum')));

        $user = User::where('email', $args['email'])->first();
        if (!$user) {
            throw new AuthenticationException();
        }

        return $guard->loginUsingId($user->id);
    }
}
