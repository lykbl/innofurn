<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\ProductVariant;

use Illuminate\Support\Str;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class FindProducts extends ProductVariantQuery
{
    /**
     * {
     *   options: string[],
     *   type: string|null,
     *   name: string|null,
     *   collection: string|null,
     *   brand: string|null,
     *   price: int|null,
     *   isFeatured: bool|null
     *  }.
     */
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->productVariantService->buildSearchQuery(...$args['filter'], orderBy: ProductVariantOrderByEnum::from(Str::lower($args['orderBy'])));
    }
}
