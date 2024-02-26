<?php

declare(strict_types=1);

namespace App\GraphQL\Exceptions\User;

use Exception;
use GraphQL\Error\ClientAware;
use GraphQL\Error\ProvidesExtensions;

final class AuthenticationException extends Exception implements ClientAware, ProvidesExtensions
{
    private string $reason = 'Authentication failed';

    public function __construct($message = 'Authentication failed')
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
