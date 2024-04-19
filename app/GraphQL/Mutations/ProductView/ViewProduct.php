<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\ProductView;

use App\Services\ProductViewService;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ViewProduct
{
    public function __construct(private ProductViewService $productViewService)
    {
    }

    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): bool
    {
        return $this->productViewService->recordProductView($args['slug'], $context->user()->id);
    }
}
