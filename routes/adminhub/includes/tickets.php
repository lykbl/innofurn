<?php

use App\Http\Livewire\Pages\Chat\ChatsIndex;
use App\Http\Livewire\Pages\Chat\ChatsJoin;
use Illuminate\Support\Facades\Route;

/**
 * Channel routes.
 */
Route::group([
//    'middleware' => 'can:chats:manage-tickets',
], function () {
    Route::get('/', ChatsIndex::class)->name('adminhub.tickets.index');

    Route::group([
        'prefix' => '{chat}',
    ], function () {
//        Route::get('/', ChatsJoin::class)->name('adminhub.tickets.show');
    });
});
