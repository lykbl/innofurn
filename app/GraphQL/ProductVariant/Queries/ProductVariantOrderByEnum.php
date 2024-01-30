<?php

declare(strict_types=1);

namespace App\GraphQL\ProductVariant\Queries;

enum ProductVariantOrderByEnum: string
{
    case NAME_ASC   = 'name_asc';
    case NAME_DESC  = 'name_desc';
    case PRICE_ASC  = 'price_asc';
    case PRICE_DESC = 'price_desc';

    public function column(): string
    {
        return explode('_', $this->value)[0];
    }

    public function direction(): string
    {
        return explode('_', $this->value)[1];
    }
}
