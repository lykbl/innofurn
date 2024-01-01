<?php

declare(strict_types=1);

namespace App\GraphQL\Scalars;

use GraphQL\Error\Error;
use GraphQL\Error\SerializationError;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\Printer;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

class Rating extends ScalarType
{
    public string $name = 'Rating';

    /**
     * @throws SerializationError
     */
    public function serialize($value): int
    {
        if (is_int($value) && $value >= 1 && $value <= 5) {
            return $value;
        }

        $notRating = Utils::printSafe($value);
        throw new SerializationError("Rating should be a positive integer between 1 and 5: $notRating");
    }

    /**
     * @throws Error
     */
    public function parseValue($value): int
    {
        if (is_int($value) && $value >= 1 && $value <= 5) {
            return $value;
        }

        $notRating = Utils::printSafe($value);
        throw new Error("Rating must be a positive integer between 1 and 5: $notRating");
    }

    /**
     * @throws Error
     */
    public function parseLiteral(Node $valueNode, array $variables = null): string
    {
        if ($valueNode instanceof IntValueNode && $valueNode->value >= 1 && $valueNode->value <= 5) {
            return $valueNode->value;
        }

        $notRating = Printer::doPrint($valueNode);
        throw new Error("Rating must be a positive integer between 1 and 5: $notRating");
    }
}
