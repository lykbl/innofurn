<?php

declare(strict_types=1);

namespace App\GraphQL\Scalars;

use Cartalyst\Converter\Converter;
use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;

class Dimension extends ScalarType
{
    /**
     * @param ?Converter $value
     *
     * @return ?array
     */
    public function serialize(mixed $value): ?array
    {
        if (!$value) {
            // TODO throw error?
            return null;
        }

        return [
            'format' => $value->convert()->format(),
            'value'  => $value->getValue(),
            'unit'   => $value->getTo(),
        ];
    }

    /**
     * @param Converter $value
     *
     * @return ?float
     */
    public function parseValue(mixed $value): ?float
    {
        return $value ? $value->getValue() : null;
    }

    public function parseLiteral(Node $valueNode, ?array $variables = null): void
    {
        throw new Error('Dimension scalar can not be used as an argument');
    }
}
