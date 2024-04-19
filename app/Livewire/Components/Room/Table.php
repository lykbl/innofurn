<?php

declare(strict_types=1);

namespace App\Livewire\Components\Room;

use App\Models\Room;
use Illuminate\Support\Collection;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Hub\Models\SavedSearch;
use Lunar\LivewireTables\Components\Actions\Action;
use Lunar\LivewireTables\Components\Columns\TextColumn;
use Lunar\LivewireTables\Components\Filters\CheckboxFilter;
use Lunar\LivewireTables\Components\Table as BaseTable;

class Table extends BaseTable
{
    use Notifies;

    protected $tableBuilderBinding = TableBuilder::class;

    protected $listeners = [
        'saveSearch' => 'handleSaveSearch',
    ];

    public bool $searchable = true;

    public bool $canSaveSearches = true;

    public function build(): void
    {
        $this->tableBuilder->baseColumns([
            TextColumn::make('id'),
            TextColumn::make('name')
                ->value(fn (Room $room) => $room->translateAttribute('name'))
                ->url(fn (Room $room) => route('hub.room.show', $room->id)),
            TextColumn::make('description')->value(fn (Room $room) => $room->translateAttribute('description')),
            TextColumn::make('active')->value(fn (Room $room) => $room->active ? 'Active' : 'Disabled'),
        ]);

        $filterKey = 'active';
        $this->tableBuilder->addFilter(
            CheckboxFilter::make($filterKey)
                ->heading(__("adminhub::tables.filters.rooms.$filterKey"))
                ->query(function ($filters, $query) use ($filterKey): void {
                    $value = $filters->get($filterKey);
                })
        );

        $this->tableBuilder->addAction(
            Action::make('view')
                ->label(__('adminhub::tables.actions.rooms.show'))
                ->url(fn ($record) => route('hub.rooms.show', $record->id))
        );
    }

    public function getSearchPlaceholderProperty(): string
    {
        return __('adminhub::tables.search_placeholders.rooms');
    }

    public function deleteSavedSearch($id): void
    {
        SavedSearch::destroy($id);

        $this->resetSavedSearch();

        $this->notify(
            __('adminhub::notifications.saved_searches.deleted')
        );
    }

    public function saveSearch(): void
    {
        $this->validateOnly('savedSearchName', [
            'savedSearchName' => 'required',
        ]);

        auth()->getUser()->savedSearches()->create([
            'name'      => $this->savedSearchName,
            'term'      => $this->query,
            'component' => $this->getName(),
            'filters'   => $this->filters,
        ]);

        $this->notify('Search saved');

        $this->savedSearchName = null;

        $this->emit('savedSearch');
    }

    public function getSavedSearchesProperty(): Collection
    {
        return auth()->getUser()->savedSearches()->whereComponent(
            $this->getName()
        )->get()->map(function ($savedSearch) {
            return [
                'key'     => $savedSearch->id,
                'label'   => $savedSearch->name,
                'filters' => $savedSearch->filters,
                'query'   => $savedSearch->term,
            ];
        });
    }

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
