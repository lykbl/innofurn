<?php

declare(strict_types=1);

namespace App\GraphQL\Types\ProductVariant;

use App\Domains\ProductVariant\ProductVariant;

use function count;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Collection;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class Attributes
{
    public function __invoke(ProductVariant $productVariant, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Collection
    {
        $attributesRelation = $productVariant->mappedAttributes();
        if (count($args['handles'] ?? [])) {
            $attributesRelation = $attributesRelation->whereIn('handle', $args['handles']);
        }
        if ($args['filterable'] ?? null) {
            $attributesRelation = $attributesRelation->where('filterable', $args['filterable']);
        }

        return $attributesRelation->get();
    }
}
