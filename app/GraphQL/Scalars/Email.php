<?php

declare(strict_types=1);

namespace App\GraphQL\Scalars;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use GraphQL\Error\Error;
use GraphQL\Error\SerializationError;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Language\Printer;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;

class Email extends ScalarType
{
    public string $name = 'Email';

    /** @throws SerializationError */
    public function serialize($value): string
    {
        if ($this->isValid($value)) {
            return $value;
        }

        $notEmail = Utils::printSafe($value);
        throw new SerializationError("Email can only represent an email: $notEmail");
    }

    /** @throws Error */
    public function parseValue($value): int
    {
        if ($this->isValid($value)) {
            return $value;
        }

        $notEmail = Utils::printSafeJson($value);
        throw new Error("Email can only represent an email: $notEmail");
    }

    public function parseLiteral(Node $valueNode, array $variables = null): string
    {
        if ($valueNode instanceof StringValueNode && $this->isValid($valueNode->value)) {
            return $valueNode->value;
        }

        $notEmail = Printer::doPrint($valueNode);
        throw new Error("Email can only represent an email: $notEmail", $valueNode);
    }

    private function isValid($value): bool
    {
        return is_string($value) && (new EmailValidator())->isValid($value, new RFCValidation());
    }
}
