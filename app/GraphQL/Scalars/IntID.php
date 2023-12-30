<?php declare(strict_types=1);

namespace App\GraphQL\Scalars;

use GraphQL\Error\Error;
use GraphQL\Error\SerializationError;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\Node;
use GraphQL\Language\Printer;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

class IntID extends ScalarType
{
    public string $name = 'IntID';

    public ?string $description = '
        The `IntID` scalar type represents a unique integer identifier, often used to
        refetch an object or as a key for a cache. The IntID type appears in a JSON
        response as an integer; however, it is not intended to be human-readable.
        When expected as an input type, any integer input value will be accepted as an IntID.
    ';

    /** @throws SerializationError */
    public function serialize($value): int
    {
        $canCast = \is_int($value);

        if (!$canCast || $value <= 0) {
            $notID = Utils::printSafe($value);
            throw new SerializationError("IntID can only represent a positive integer: {$notID}");
        }

        return $value;
    }

    /** @throws Error */
    public function parseValue($value): int
    {
        if (\is_int($value) && $value > 0) {
            return $value;
        }

        $notID = Utils::printSafeJson($value);
        throw new Error("ID can only represent a positive integer: {$notID}");
    }

    public function parseLiteral(Node $valueNode, array $variables = null): string
    {
        if ($valueNode instanceof IntValueNode) {
            return $valueNode->value;
        }

        $notID = Printer::doPrint($valueNode);
        throw new Error("ID can only represent a positive integer: {$notID}", $valueNode);
    }
}
