<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use Illuminate\Contracts\Auth\Authenticatable;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class SignUp extends UserMutation
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Authenticatable
    {
        return $this->userService->signUp(
            $args['email'],
        );
    }
}
