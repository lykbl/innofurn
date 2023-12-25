<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Translatable as TranslatableModel;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

abstract class Translatable
{
    /**
     * @param TranslatableModel $product
     * @param array             $args
     * @param GraphQLContext    $context
     * @param ResolveInfo       $resolveInfo
     *
     * @return string|null
     */
    public function __invoke(TranslatableModel $product, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ?string
    {
        return $product->translateAttribute($resolveInfo->fieldName, $args['lang'] ?? 'en');
    }
}
