<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\ProductVariant;

use Laravel\Scout\Builder;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class FindProductVariants extends ProductVariantQuery
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Builder
    {
        return $this->productVariantService->findProductVariants(
            search: $args['search'] ?? '',
            perPage: $args['first'] ?? 20,
            page: $args['page'] ?? 1,
        );
    }
}
