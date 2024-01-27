<?php

declare(strict_types=1);

use App\Livewire\Chat\Pages\ChatRoomShow;
use App\Livewire\Chat\Pages\ChatRoomsIndex;
use Illuminate\Support\Facades\Route;

Route::group([
//    'middleware' => 'can:chats:manage-chats', //TODO implement when roles are ready
], function (): void {
    Route::get('/', ChatRoomsIndex::class)->name('adminhub.chats.index');

    Route::group([
        'prefix' => '{chatRoom}',
    ], function (): void {
        Route::get('/', ChatRoomShow::class)->name('adminhub.chats.show');
    });
});
