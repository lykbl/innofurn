<?php

declare(strict_types=1);

namespace App\GraphQL\Product\Queries;

use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class OptionFiltersForCollection extends ProductQuery
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->productService->collectionFilters($args['productTypeId']);
    }
}
