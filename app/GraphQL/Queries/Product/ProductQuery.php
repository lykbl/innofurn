<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Product;

use App\GraphQL\ResolverInterface;
use App\Services\ProductService;

abstract class ProductQuery implements ResolverInterface
{
    public function __construct(protected ProductService $productService)
    {
    }
}
