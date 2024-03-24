<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Review;

use App\GraphQL\ResolverInterface;
use App\Services\Review\ReviewService;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

abstract class ReviewQuery implements ResolverInterface
{
    public function __construct(protected readonly ReviewService $reviewService)
    {
    }

    abstract public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo);
}
