<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Review;

use App\GraphQL\ResolverInterface;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

abstract class ReviewQuery implements ResolverInterface
{
    //    public function __construct(private readonly UserService $userService)
    //    {
    //    }

    abstract public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo);
}
