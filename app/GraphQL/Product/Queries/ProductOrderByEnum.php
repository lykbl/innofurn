<?php

declare(strict_types=1);

namespace App\GraphQL\Product\Queries;

enum ProductOrderByEnum: string
{
    case NAME_ASC   = 'NAME_ASC';
    case NAME_DESC  = 'NAME_DESC';
    case PRICE_ASC  = 'PRICE_ASC';
    case PRICE_DESC = 'PRICE_DESC';

    public function column(): string
    {
        return explode('_', $this->value)[0];
    }

    public function direction(): string
    {
        return explode('_', $this->value)[1];
    }
}
