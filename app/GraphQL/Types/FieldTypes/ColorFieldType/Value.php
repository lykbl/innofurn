<?php

declare(strict_types=1);

namespace App\GraphQL\Types\FieldTypes\ColorFieldType;

use App\FieldTypes\ColorFieldType;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Value
{
    public function __invoke(ColorFieldType $colorFieldType, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ?string
    {
        return $colorFieldType->getValue();
    }
}
