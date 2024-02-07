<?php

declare(strict_types=1);

namespace App\GraphQL\Product\Queries;

use Illuminate\Support\Str;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class FindProducts extends ProductQuery
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->productVariantService->buildSearchQuery(
            filters: $args['filters'],
            orderBy: ProductOrderByEnum::from(Str::lower($args['orderBy'])),
        );
    }
}
