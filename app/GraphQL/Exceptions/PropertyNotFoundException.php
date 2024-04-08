<?php

declare(strict_types=1);

namespace App\GraphQL\Exceptions;

use Exception;

class PropertyNotFoundException extends Exception
{
    public function __construct(string $message = 'Property missing from class')
    {
        parent::__construct($message);
    }
}
