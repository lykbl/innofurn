<?php

namespace App\GraphQL\Mutations\User;

use App\GraphQL\ResolverInterface;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Contracts\Auth\Authenticatable;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

abstract class UserMutation implements ResolverInterface
{
    public function __construct(protected readonly UserService $userService)
    {
    }

    abstract public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Authenticatable|User;
}
