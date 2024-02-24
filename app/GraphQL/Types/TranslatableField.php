<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Models\Translatable as TranslatableModel;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

abstract class TranslatableField
{
    /**
     * @param TranslatableModel $translatable
     * @param array             $args
     * @param GraphQLContext    $context
     * @param ResolveInfo       $resolveInfo
     *
     * @return string|null
     */
    public function __invoke(TranslatableModel $translatable, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ?string
    {
        return $translatable->translate($resolveInfo->fieldName, $args['lang'] ?? 'en'); // TODO read lang from user context
    }
}
