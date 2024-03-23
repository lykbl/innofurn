<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\ProductVariant;

enum ProductVariantOrderByEnum: string
{
    case NAME_ASC        = 'NAME_ASC';
    case NAME_DESC       = 'NAME_DESC';
    case PRICE_ASC       = 'PRICE_ASC';
    case PRICE_DESC      = 'PRICE_DESC';
    case AVG_RATING_ASC  = 'AVG_RATING_ASC';
    case AVG_RATING_DESC = 'AVG_RATING_DESC';

    public function key(?string $currencyCode = null): string
    {
        return match ($this) {
            self::NAME_ASC, self::NAME_DESC => 'name',
            self::PRICE_ASC, self::PRICE_DESC => "prices.$currencyCode",
            self::AVG_RATING_ASC, self::AVG_RATING_DESC => 'rating',
        };
    }

    public function direction(): string
    {
        return str_contains($this->value, 'ASC') ? 'asc' : 'desc';
    }
}
