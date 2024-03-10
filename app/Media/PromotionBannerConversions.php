<?php

declare(strict_types=1);

namespace App\Media;

use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;

class PromotionBannerConversions
{
    /**
     * @throws InvalidManipulation
     */
    public function apply($model): void
    {
        // isolate to specific models
        $conversions = [
            'promotion_banner_panel' => [
                'width'  => 1600,
                'height' => 300,
            ],
            'promotion_banner_card' => [
                'width'  => 400,
                'height' => 400,
            ],
            'promotion_banner_carousel_item' => [
                'width'  => 250,
                'height' => 150,
            ],
        ];

        foreach ($conversions as $key => $conversion) {
            $model->addMediaConversion($key)
                ->fit(
                    Manipulations::FIT_FILL,
                    $conversion['width'],
                    $conversion['height']
                )->keepOriginalImageFormat();
        }
    }
}
