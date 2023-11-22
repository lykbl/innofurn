<?php

declare(strict_types=1);

namespace App\GraphQL\Scalars;

use Cartalyst\Converter\Converter;
use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;

class Price extends ScalarType
{
    /**
     * @param Price $value
     *
     * @return array
     */
    public function serialize(mixed $value): array
    {
        return [
            'format'        => $value->price->formatted(),
            'value'         => $value->price->value,
            'currency_code' => $value->price->currency->code,
            'currency_name' => $value->price->currency->name,
        ];
    }

    /**
     * @param Converter $value
     *
     * @return float
     */
    public function parseValue(mixed $value): float
    {
        return $value->getValue();
    }

    public function parseLiteral(Node $valueNode, array $variables = null): void
    {
        throw new Error('Dimension scalar can not be used as an argument');
    }
}