<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Review;

use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ApproveReview extends ReviewMutation
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): bool
    {
        return $this->reviewService->approve($args['id']);
    }
}
