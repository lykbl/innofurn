<?php

namespace App\GraphQL\Mutations\ProductView;

use App\GraphQL\ResolverInterface;
use App\Services\ProductViewService;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final readonly class RecordProductView implements ResolverInterface
{
    public function __construct(protected ProductViewService $productViewService)
    {
    }

    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): bool
    {
        return (bool) $this->productViewService->recordProductView(
            productId: $args['productId'] ?? null,
            user: $context->user() ?? null,
        );
    }
}
