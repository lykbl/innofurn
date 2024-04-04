<?php

namespace App\GraphQL\Exceptions;

class PropertyNotFoundException extends \Exception
{
    public function __construct(string $message = 'Property missing from class')
    {
        parent::__construct($message);
    }

}
