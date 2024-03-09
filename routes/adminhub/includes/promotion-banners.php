<?php

declare(strict_types=1);

use App\Livewire\Pages\PromotionBanner\Create;
use App\Livewire\Pages\PromotionBanner\Index;
use App\Livewire\Pages\PromotionBanner\Show;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'can:catalogue:manage-banners',
], function (): void {
    Route::get('/', Index::class)->name('hub.promotion-banners.index');

    Route::get('/create', Create::class)->name('hub.promotion-banners.create');

    Route::group([
        'prefix' => 'types',
    ], function (): void {
        Route::get('/', \App\Livewire\Pages\PromotionBanner\Types\Index::class)
            ->name('hub.promotion-banner-types.index');
        Route::get('/create', \App\Livewire\Pages\PromotionBanner\Types\Create::class)
            ->name('hub.promotion-banner-types.create');

        Route::group([
            'prefix' => '{promotionBannerType}',
        ], function (): void {
            Route::get('/', \App\Livewire\Pages\PromotionBanner\Types\Show::class)
                ->name('hub.promotion-banner-types.show');
        });
    });

    Route::group([
        'prefix' => '{id}',
    ], function (): void {
        Route::get('/', Show::class)->name('hub.promotion-banners.show');
    });
});
