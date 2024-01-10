<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\User;

use App\GraphQL\ResolverInterface;
use App\Services\User\UserService;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

abstract class UserQuery implements ResolverInterface
{
    public function __construct(private readonly UserService $userService)
    {
    }

    abstract public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo);
}
