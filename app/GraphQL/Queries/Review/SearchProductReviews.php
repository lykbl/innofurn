<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Review;

use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SearchProductReviews extends ReviewQuery
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->reviewService->searchProductReviews(
            user: $context->user ?? null,
            filters: $args['filters'] ?? [],
            page: $args['page'] ?? 1,
            perPage: $args['first'] ?? 10,
            orderBy: isset($args['orderBy']) ? ReviewOrderByEnum::from($args['orderBy']) : ReviewOrderByEnum::RATING_DESC,
        );
    }
}
