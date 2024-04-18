<?php

declare(strict_types=1);

use App\Livewire\Pages\PromotionBanner\Create;
use App\Livewire\Pages\PromotionBanner\Index;
use App\Livewire\Pages\PromotionBanner\Show;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'can:catalogue:manage-rooms',
], function (): void {
    Route::get('/', Index::class)->name('hub.rooms.index');

    Route::get('/create', Create::class)->name('hub.rooms.create');

    Route::group([
        'prefix' => '{id}',
    ], function (): void {
        Route::get('/', Show::class)->name('hub.rooms.show');
    });
});
