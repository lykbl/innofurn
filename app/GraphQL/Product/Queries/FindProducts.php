<?php

declare(strict_types=1);

namespace App\GraphQL\Product\Queries;

use App\GraphQL\ProductVariant\Queries\ProductVariantOrderByEnum;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class FindProducts extends ProductQuery
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->productVariantService->buildSearchQuery(...$args['filter'], orderBy: ProductVariantOrderByEnum::from(Str::lower($args['orderBy'])));
    }
}
