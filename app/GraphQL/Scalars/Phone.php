<?php

declare(strict_types=1);

namespace App\GraphQL\Scalars;

use GraphQL\Error\Error;
use GraphQL\Error\SerializationError;
use GraphQL\Language\AST\Node;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;

class Phone extends ScalarType
{
    public string $name = 'Phone';

    /** @throws SerializationError */
    public function serialize($value): string
    {
        if ($this->isValid($value)) {
            return $value;
        }

        $notPhone = Utils::printSafe($value);
        throw new SerializationError("$this->name can only represent a phone: $notPhone");
    }

    /** @throws Error */
    public function parseValue($value): string
    {
        if ($this->isValid($value)) {
            return $value;
        }

        $notPhone = Utils::printSafeJson($value);
        throw new Error("$this->name can only represent a phone: $notPhone");
    }

    public function parseLiteral(Node $valueNode, array $variables = null): string
    {
        if ($valueNode instanceof StringValueNode && $this->isValid($valueNode->value)) {
            return $valueNode->value;
        }

        $notPhone = Utils::printSafe($valueNode);
        throw new Error("$this->name can only represent a phone: $notPhone");
    }

    private function isValid($value): bool
    {
        try {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $phone     = $phoneUtil->parse($value);

            return $phoneUtil->isValidNumber($phone);
        } catch (NumberParseException) {
            return false;
        }
    }
}
