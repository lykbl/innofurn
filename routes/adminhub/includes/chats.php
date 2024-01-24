<?php

declare(strict_types=1);

use App\Livewire\Chat\Pages\ChatRoomShow;
use App\Livewire\Chat\Pages\ChatRoomsIndex;
use Illuminate\Support\Facades\Route;

/*
 * Channel routes.
 */
Route::group([
//    'middleware' => 'can:chats:manage-chats',
], function (): void {
    Route::get('/', ChatRoomsIndex::class)->name('adminhub.chats.index');

    Route::group([
        'prefix' => '{chat}',
    ], function (): void {
        Route::get('/', ChatRoomShow::class)->name('adminhub.chats.show');
        //        Route::get('/', fn () => view('adminhub.livewire.pages.chats.show'))->name('adminhub.chats.show');
    });
});
