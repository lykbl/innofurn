<?php

declare(strict_types=1);

namespace App\GraphQL\Product\Queries;

use App\Domains\Product\Product;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ProductDetails extends ProductQuery
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Product
    {
        return $this->productService->findBySlug($args['slug']);
    }
}
