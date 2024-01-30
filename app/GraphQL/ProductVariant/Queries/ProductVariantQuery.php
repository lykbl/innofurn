<?php

declare(strict_types=1);

namespace App\GraphQL\ProductVariant\Queries;

use App\Domains\ProductVariant\ProductVariantService;
use App\GraphQL\ResolverInterface;

abstract class ProductVariantQuery implements ResolverInterface
{
    public function __construct(protected ProductVariantService $productVariantService)
    {
    }
}
