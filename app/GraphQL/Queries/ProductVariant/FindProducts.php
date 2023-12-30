<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\ProductVariant;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class FindProducts extends ProductVariantQuery
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Builder
    {
        return $this->productVariantService->buildSearchQuery(...$args['filter'], orderBy: ProductVariantOrderByEnum::from(Str::lower($args['orderBy'])));
    }
}
