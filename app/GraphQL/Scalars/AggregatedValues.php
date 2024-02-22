<?php

declare(strict_types=1);

namespace App\GraphQL\Scalars;

use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;

class AggregatedValues extends ScalarType
{
    public function serialize(mixed $value): string|array
    {
        return $value;
    }

    public function parseValue(mixed $value): void
    {
        throw new Error('Aggregated data scalar can not be used as an input');
    }

    public function parseLiteral(Node $valueNode, array $variables = null): void
    {
        throw new Error('Aggregated data scalar can not be used as an input');
    }
}
