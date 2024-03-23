<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\ProductVariant;

use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class OptionFiltersForCollection extends ProductVariantQuery
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->productVariantService->collectionFilters($args['slug']);
    }
}
