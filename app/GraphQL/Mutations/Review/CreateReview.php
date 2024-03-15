<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Review;

use App\Models\Review\Review;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateReview extends ReviewMutation
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Review
    {
        return $this->reviewService->create(...array_merge(['userId' => $context->user()->id], $args));
    }
}
