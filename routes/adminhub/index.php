<?php

declare(strict_types=1);

use Lunar\Hub\Http\Middleware\Authenticate;

Route::group([
    'prefix'     => config('lunar-hub.system.path', 'hub'),
    'middleware' => config('lunar-hub.system.middleware', ['web']),
], function (): void {
    Route::group([
        'middleware' => [
            Authenticate::class,
        ],
    ], function ($router): void {
        Route::group([
            'prefix' => 'chats',
        ], __DIR__.'/includes/chats.php');
        Route::group([
            'prefix' => 'tickets',
        ], __DIR__.'/includes/tickets.php');
        Route::group([
            'prefix' => 'promotion-banners',
        ], __DIR__.'/includes/promotion-banners.php');
    });
});
