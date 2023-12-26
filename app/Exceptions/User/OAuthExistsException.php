<?php

declare(strict_types=1);

namespace App\Exceptions\User;

use Exception;
use Throwable;

class OAuthExistsException extends Exception
{
    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        $message = $message ?: 'User was created with different method';
        parent::__construct($message, $code, $previous);
    }
}
