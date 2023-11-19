<?php

namespace App\GraphQL\Exceptions;

use Throwable;
use GraphQL\Error\Error;

class UnknownDimensionException extends Error
{
    public function __construct(string $dimension, int $code = 400, ?Throwable $previous = null)
    {
        $message = "Dimension $dimension type is not supported";
        parent::__construct($message, $code, $previous);
    }
}
