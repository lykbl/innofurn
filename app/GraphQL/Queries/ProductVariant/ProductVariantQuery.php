<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\ProductVariant;

use App\GraphQL\ResolverInterface;
use App\Services\ProductVariantService;

abstract class ProductVariantQuery implements ResolverInterface
{
    public function __construct(protected ProductVariantService $productVariantService)
    {
    }
}
