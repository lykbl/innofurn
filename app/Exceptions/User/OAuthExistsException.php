<?php

declare(strict_types=1);

namespace App\Exceptions\User;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class OAuthExistsException extends Exception
{
    public function __construct(string $message = '', int $code = Response::HTTP_BAD_REQUEST, Throwable $previous = null)
    {
        $message = $message ?: 'User was created with a different method';
        parent::__construct($message, $code, $previous);
    }
}
