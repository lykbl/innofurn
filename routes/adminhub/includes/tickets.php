<?php

declare(strict_types=1);

use App\Http\Livewire\Pages\Ticket\TicketsIndex;
use Illuminate\Support\Facades\Route;

/*
 * Channel routes.
 */
Route::group([
//    'middleware' => 'can:chats:manage-tickets',
], function (): void {
    Route::get('/', TicketsIndex::class)->name('adminhub.tickets.index');

    Route::group([
        'prefix' => '{chat}',
    ], function (): void {
        //        Route::get('/', ChatsJoin::class)->name('adminhub.tickets.show');
    });
});
