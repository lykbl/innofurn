<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Product;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class FindProducts extends ProductQuery
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): LengthAwarePaginator
    {
        $paginator = $this->productService->findProducts(
            perPage: $args['first'] ?? 20,
            page: $args['page'] ?? 1,
            filters: $args['filters'] ?? [],
            orderBy: isset($args['orderBy']) ? ProductOrderByEnum::from($args['orderBy']) : ProductOrderByEnum::PRICE_DESC,
        );

        return $paginator;
    }
}
