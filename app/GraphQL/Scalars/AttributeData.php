<?php

declare(strict_types=1);

namespace App\GraphQL\Scalars;

use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;

class AttributeData extends ScalarType
{
    public function serialize(mixed $value)
    {
        if (!$value) {
            // TODO throw error?
            return null;
        }

        return $value;
    }

    public function parseValue(mixed $value)
    {
        return $value ? $value->getValue() : null;
    }

    public function parseLiteral(Node $valueNode, array $variables = null): void
    {
        //        throw new Error('Dimension scalar can not be used as an argument');
    }
}
