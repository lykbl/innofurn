<?php

declare(strict_types=1);

namespace App\Models\PromotionBanner;

enum PromotionBannerStyle: string
{
    case Splash       = 'splash';
    case Panel        = 'panel';
    case CarouselItem = 'carousel_item';
}
