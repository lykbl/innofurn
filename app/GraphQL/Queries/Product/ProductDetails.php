<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Product;

use App\Models\Product;
use Log;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ProductDetails extends ProductQuery
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Product
    {
        Log::info('ProductDetails');

        return $this->productService->findBySlug($args['slug']);
    }
}
