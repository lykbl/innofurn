<?php

namespace App\GraphQL\Types;

use App\Models\Product;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

abstract class Translatable
{
    /**
     * @param Product $product
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return string|null
     *
     */
    public function __invoke(Product $product, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ?string
    {
        return $product->translateAttribute($resolveInfo->fieldName, $args['lang'] ?? 'en');
    }
}
