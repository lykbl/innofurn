<?php

declare(strict_types=1);

namespace App\GraphQL\Exceptions;

use GraphQL\Error\Error;
use Throwable;

class UnknownDimensionException extends Error
{
    public function __construct(string $dimension, int $code = 400, ?Throwable $previous = null)
    {
        $message = "Dimension $dimension type is not supported";
        parent::__construct($message, $code, $previous);
    }
}
