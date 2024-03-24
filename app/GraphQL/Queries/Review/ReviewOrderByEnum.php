<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Review;

enum ReviewOrderByEnum: string
{
    case RATING_ASC      = 'RATING_ASC';
    case RATING_DESC     = 'RATING_DESC';
    case CREATED_AT_ASC  = 'CREATED_AT_ASC';
    case CREATED_AT_DESC = 'CREATED_AT_DESC';

    public function key(): string
    {
        return match ($this) {
            self::RATING_ASC, self::RATING_DESC => 'rating',
            self::CREATED_AT_ASC, self::CREATED_AT_DESC => 'created_at',
        };
    }

    public function direction(): string
    {
        return str_contains($this->value, 'ASC') ? 'asc' : 'desc';
    }
}
