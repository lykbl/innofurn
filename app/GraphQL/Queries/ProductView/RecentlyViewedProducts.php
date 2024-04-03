<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\ProductView;

use App\GraphQL\ResolverInterface;
use App\Services\ProductViewService;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class RecentlyViewedProducts implements ResolverInterface
{
    public function __construct(private ProductViewService $productViewService)
    {
    }

    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return $this->productViewService->recentlyViewedProducts($context->user());
    }
}
