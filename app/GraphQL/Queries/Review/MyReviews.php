<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Review;

use App\Models\Review\Review;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class MyReviews extends ReviewQuery
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return Review::where('user_id', $context->user()->id);
    }
}
