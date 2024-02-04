<?php

declare(strict_types=1);

namespace App\GraphQL\Scalars;

use Cartalyst\Converter\Converter;
use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;
use Lunar\Base\Casts\Price as PriceDataCast;

class PriceData extends ScalarType
{
    /**
     * @param PriceDataCast $value
     *
     * @return array
     */
    public function serialize(mixed $value): array
    {
        return [
            'format'       => $value->formatted(),
            'value'        => $value->value,
            'currencyCode' => $value->currency->code,
            'currencyName' => $value->currency->name,
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
        throw new Error('Price scalar can not be used as an argument');
    }
}
