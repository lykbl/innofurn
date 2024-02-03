<?php

declare(strict_types=1);

namespace App\GraphQL\Types\ProductVariant;

use App\Domains\ProductVariant\ProductVariant;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Collection;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class Images
{
    public function __invoke(ProductVariant $productVariant, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Collection
    {
        $attributesRelation = $productVariant->images();
        if ($args['primaryOnly'] ?? false) {
            $attributesRelation = $attributesRelation->where('primary', true);
        }

        return $attributesRelation->get();
    }
}
