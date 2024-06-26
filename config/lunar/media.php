<?php

declare(strict_types=1);

use App\Media\PromotionBannerConversions;
use App\Media\StorefrontConversions;
use Lunar\Base\StandardMediaConversions;

return [
    'conversions' => [
        StandardMediaConversions::class,
        StorefrontConversions::class,
        PromotionBannerConversions::class,
    ],

    'fallback' => [
        'url'  => env('FALLBACK_IMAGE_URL', null),
        'path' => env('FALLBACK_IMAGE_PATH', null),
    ],
];
