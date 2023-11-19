<?php

declare(strict_types=1);

namespace App\GraphQL\Types\Media;

use App\Models\Media;

final class Conversions
{
    /** @param  array{types: array<string>}  $args */
    public function __invoke(Media $media, array $args): array
    {
        $urls = [];
        foreach ($args['types'] ?? ['medium'] as $type) {
            $urls[] = $media->getUrl($type);
        }

        return $urls;
    }
}
