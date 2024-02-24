<?php

declare(strict_types=1);

namespace App\GraphQL\Product\Exceptions;

use Exception;
use GraphQL\Error\ClientAware;
use GraphQL\Error\ProvidesExtensions;

final class ProductNotFoundException extends Exception implements ClientAware, ProvidesExtensions
{
    private string $reason = 'Product not found';

    public function __construct($message = 'Product not found')
    {
        parent::__construct($message);
    }

    /**
     * Returns true when exception message is safe to be displayed to a client.
     */
    public function isClientSafe(): bool
    {
        return true;
    }

    /**
     * Data to include within the "extensions" key of the formatted error.
     *
     * @return array<string, mixed>
     */
    public function getExtensions(): array
    {
        return [
            'some'   => '',
            'reason' => $this->reason,
        ];
    }
}
