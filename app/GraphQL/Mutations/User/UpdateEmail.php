<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateEmail extends UserMutation
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Authenticatable|User
    {
        return $this->userService->updateEmail(
            user: $context->user(),
            email: $args['email']
        );
    }
}
