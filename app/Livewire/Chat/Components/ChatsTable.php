<?php

declare(strict_types=1);

namespace App\Livewire\Chat\Components;

use App\Models\Chat\ChatRoom;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Lunar\LivewireTables\Components\Actions\Action;
use Lunar\LivewireTables\Components\Columns\TextColumn;
use Lunar\LivewireTables\Components\Filters\CheckboxFilter;
use Lunar\LivewireTables\Components\Table;

class ChatsTable extends Table
{
    protected $tableBuilderBinding = ChatRoomsTableBuilder::class;

    protected $listeners = [
        'saveSearch' => 'handleSaveSearch',
    ];

    public bool $searchable = true;

    public bool $canSaveSearches = true;

    /**
     * {@inheritDoc}
     */
    public function build(): void
    {
        $filterKey = 'active_only';
        $this->tableBuilder->addFilter(
            CheckboxFilter::make($filterKey)
                ->heading(__("adminhub::tables.filters.chat_room.$filterKey"))
                ->query(function ($filters, $query) use ($filterKey): void {
                    $value = $filters->get($filterKey);

                    if ($value) {
                        $query->whereNull('closed_at');
                    }
                })
        );

        $filterKey = 'awaiting_reply_only';
        $this->tableBuilder->addFilter(
            CheckboxFilter::make($filterKey)
                ->heading(__("adminhub::tables.filters.chat_room.$filterKey"))
                ->query(function (Collection $filters, Builder $query) use ($filterKey): void {
                    $value = $filters->get($filterKey);

                    if ($value) {
                        $query->whereHas('messages', function (Builder $query): void {
                            $query->where('status', 'delivered')->whereNull('staff_id');
                        });
                    }
                }
                )
        );

        $this->tableBuilder->baseColumns([
            TextColumn::make('id'),
            TextColumn::make('customer')->value(function (ChatRoom $chatRoom) {
                return "{$chatRoom->customer->name} ({$chatRoom->customer->id})";
            }),
            TextColumn::make('closed_at')->value(function (ChatRoom $chatRoom) {
                return $chatRoom->closed_at ?: 'Active';
            }),
            TextColumn::make('has_pending_messages')->value(function (ChatRoom $chatRoom) {
                $pendingMessagesCount = $chatRoom->messages->where('status', 'delivered')->whereNull('staff_id')->count();

                return $pendingMessagesCount > 0 ? "Yes ($pendingMessagesCount)" : 'No';
            }),
        ]);

        $this->tableBuilder->addAction(Action::make('view')->label(__('adminhub::tables.actions.chat_room.show'))->url(function ($record) {
            return route('adminhub.chats.show', $record->id);
        }));
    }

    /**
     * Return the search placeholder.
     *
     * @return string
     */
    public function getSearchPlaceholderProperty(): string
    {
        return __('adminhub::tables.search_placeholders.chat_room');
    }

    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        $filters = $this->filters;
        $query   = $this->query;

        if ($this->savedSearch) {
            $search = $this->savedSearches->first(function ($search) {
                return $search['key'] == $this->savedSearch;
            });

            if ($search) {
                $filters = $search['filters'];
                $query   = $search['query'];
            }
        }

        return $this->tableBuilder
            ->searchTerm($query)
            ->queryStringFilters($filters)
            ->perPage($this->perPage)
            ->getData();
    }
}
