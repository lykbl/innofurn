<?php

declare(strict_types=1);

namespace App\GraphQL\Directives\Pagination;

use Nuwave\Lighthouse\Pagination\PaginationType as BasePaginationType;

/**
 * An enum-like class that contains the supported types of pagination.
 */
class PaginationType extends BasePaginationType
{
    public const SCOUT = 'SCOUT'; // TODO rename to meilisearch?

    public function __construct(string $paginationType)
    {
        if (self::SCOUT === $paginationType) {
            $this->type = self::SCOUT;
        } else {
            parent::__construct($paginationType);
        }
    }

    public function isScout(): bool
    {
        return self::SCOUT === $this->type;
    }

    public function infoFieldName(): string
    {
        return match (true) {
            self::SCOUT === $this->type => 'paginatorInfo',
            default                     => parent::infoFieldName(),
        };
    }
}
