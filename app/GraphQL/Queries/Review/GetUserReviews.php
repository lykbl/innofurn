<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Review;

use App\Models\Review\Review;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class GetUserReviews extends ReviewQuery
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $query   = Review::where('user_id', Auth::user()->id);
        $reviews = $query->get();

        return $reviews;
    }
}
