<?php

declare(strict_types=1);

namespace App\Http\Livewire\Components\Chat;

use App\Models\Chat\ChatRoom;
use Lunar\LivewireTables\Components\Actions\Action;
use Lunar\LivewireTables\Components\Columns\TextColumn;
use Lunar\LivewireTables\Components\Table;

class ChatsTable extends Table
{
    public $hasPagination = false;

    /**
     * {@inheritDoc}
     */
    public function build(): void
    {
        $this->tableBuilder->baseColumns([
            TextColumn::make('id'),
            TextColumn::make('customer')->value(function (ChatRoom $chatRoom) {
                return "{$chatRoom->customer->name} ({$chatRoom->customer->id})";
            }),
            TextColumn::make('closed_at')->value(function (ChatRoom $chatRoom) {
                return $chatRoom->closed_at ?: 'Active';
            }),
            TextColumn::make('has_pending_messages')->value(function (ChatRoom $chatRoom) {
                return $chatRoom->messages->where('status', 'delivered')->whereNull('staff_id')->count() > 0 ? 'Yes' : 'No';
            }),
        ]);

        $this->tableBuilder->addAction(Action::make('view')->label('Join Chat')->url(function ($record) {
            return route('adminhub.chats.join', $record->id);
        }));
    }

    /**
     * Return the search placeholder.
     *
     * @return string
     */
    public function getSearchPlaceholderProperty(): string
    {
        return 'Search by keyword';
    }

    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        return ChatRoom::get();
    }
}
