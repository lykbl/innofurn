<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\GraphQL\ResolverInterface;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

abstract class Query implements ResolverInterface
{
    abstract public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo);
}
