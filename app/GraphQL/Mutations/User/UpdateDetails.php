<?php

namespace App\GraphQL\Mutations\User;

use App\GraphQL\Inputs\UpdateDetailsInput;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpdateDetails extends UserMutation
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Authenticatable|User
    {
        return $this->userService->updateMe(
            user: $context->user(),
            input: new UpdateDetailsInput($args),
        );
    }
}
