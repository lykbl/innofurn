<?php

declare(strict_types=1);

namespace App\GraphQL\Product\Queries;

use App\Domains\Product\ProductService;
use App\GraphQL\ResolverInterface;

abstract class ProductQuery implements ResolverInterface
{
    public function __construct(protected ProductService $productService)
    {
    }
}
