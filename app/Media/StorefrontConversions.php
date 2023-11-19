<?php

declare(strict_types=1);

namespace App\Media;

use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;

class StorefrontConversions
{
    /**
     * @throws InvalidManipulation
     */
    public function apply($model): void
    {
        $conversions = [
            'zoom' => [
                'width'  => 500,
                'height' => 500,
            ],
            'large' => [
                'width'  => 800,
                'height' => 800,
            ],
            'medium' => [
                'width'  => 500,
                'height' => 500,
            ],
            'small' => [
                'width'  => 100,
                'height' => 100,
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
