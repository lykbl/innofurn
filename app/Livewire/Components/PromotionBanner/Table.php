<?php

declare(strict_types=1);

namespace App\Livewire\Components\PromotionBanner;

use App\Models\PromotionBanner\PromotionBanner;
use Illuminate\Support\Collection;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Hub\Models\SavedSearch;
use Lunar\LivewireTables\Components\Actions\Action;
use Lunar\LivewireTables\Components\Columns\TextColumn;
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
            TextColumn::make('name')->value(fn (PromotionBanner $promotionBanner) => $promotionBanner->translateAttribute('name')),
            TextColumn::make('description')->value(fn (PromotionBanner $promotionBanner) => $promotionBanner->translateAttribute('description')),
            TextColumn::make('discount')->value(fn (PromotionBanner $promotionBanner) => "{$promotionBanner->discount->name} ({$promotionBanner->discount->id})"),
            TextColumn::make('starts_at')->value(fn (PromotionBanner $promotionBanner) => $promotionBanner->discount->starts_at->format('Y-m-d H:i')),
            TextColumn::make('ends_at')->value(fn (PromotionBanner $promotionBanner) => $promotionBanner->discount->ends_at->format('Y-m-d H:i')),
        ]);

        $this->tableBuilder->addAction(
            Action::make('view')
                ->label(__('adminhub::tables.actions.promotion-banners.show'))
                ->url(fn ($record) => route('hub.promotion-banners.show', $record->id))
        );
    }

    public function getSearchPlaceholderProperty(): string
    {
        return __('adminhub::tables.search_placeholders.promotion-banner');
    }

    public function deleteSavedSearch($id)
    {
        SavedSearch::destroy($id);

        $this->resetSavedSearch();

        $this->notify(
            __('adminhub::notifications.saved_searches.deleted')
        );
    }

    public function saveSearch()
    {
        $this->validateOnly('savedSearchName', [
            'savedSearchName' => 'required',
        ]);

        auth()->getUser()->savedSearches()->create([
            'name' => $this->savedSearchName,
            'term' => $this->query,
            'component' => $this->getName(),
            'filters' => $this->filters,
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
                'key' => $savedSearch->id,
                'label' => $savedSearch->name,
                'filters' => $savedSearch->filters,
                'query' => $savedSearch->term,
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
