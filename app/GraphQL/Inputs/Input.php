<?php

declare(strict_types=1);

namespace App\GraphQL\Inputs;

use App\GraphQL\Exceptions\PropertyNotFoundException;

abstract class Input
{
    public function __construct(mixed ...$args)
    {
        $this->setProperties(...$args);
    }

    public function __call($name, array $arguments)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        throw new PropertyNotFoundException("Property missing from class: $name");
    }

    private function setProperties(mixed ...$args): void
    {
        if (is_array($args[0] ?? null)) {
            $args = $args[0];
        }

        foreach ($args as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
