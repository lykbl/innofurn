<?php

declare(strict_types=1);

namespace App\Livewire\Components\PromotionBanner;

use App\Models\PromotionBanner\PromotionBanner;
use App\Models\PromotionBanner\PromotionBannerType;
use Illuminate\Support\Collection;
use Lunar\Hub\Http\Livewire\Traits\Notifies;
use Lunar\Hub\Models\SavedSearch;
use Lunar\LivewireTables\Components\Actions\Action;
use Lunar\LivewireTables\Components\Columns\TextColumn;
use Lunar\LivewireTables\Components\Filters\SelectFilter;
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
                ->value(fn (PromotionBanner $promotionBanner) => $promotionBanner->translateAttribute('name'))
                ->url(fn (PromotionBanner $promotionBanner) => route('hub.promotion-banners.show', $promotionBanner->id)),
            TextColumn::make('description')->value(fn (PromotionBanner $promotionBanner) => $promotionBanner->translateAttribute('description')),
            TextColumn::make('type')
                ->value(fn (PromotionBanner $promotionBanner) => $promotionBanner->promotionBannerType->name)
                ->url(fn (PromotionBanner $promotionBanner) => route('hub.promotion-banner-types.show', $promotionBanner->promotionBannerType->id)),
            TextColumn::make('discount')
                ->value(fn (PromotionBanner $promotionBanner) => "{$promotionBanner->discount->name} ({$promotionBanner->discount->id})")
                ->url(fn (PromotionBanner $promotionBanner) => route('hub.discounts.show', $promotionBanner->discount->id)),
            TextColumn::make('starts_at')->value(fn (PromotionBanner $promotionBanner) => $promotionBanner->discount->starts_at->format('Y-m-d H:i')),
            TextColumn::make('ends_at')->value(fn (PromotionBanner $promotionBanner) => $promotionBanner->discount->ends_at->format('Y-m-d H:i')),
        ]);

        $this->tableBuilder->addFilter(
            SelectFilter::make('type')->options(function () {
                $types = PromotionBannerType::all()->mapWithKeys(function ($type) {
                    return [$type->id => $type->name];
                });

                return collect([__('adminhub::tables.filters.all')])->merge($types);
            })->query(fn ($filters, $query) => $query->when($filters->get('type'), fn ($q) => $q
                ->where(['promotion_banner_type_id' => $filters->get('type')])
            )
            )
        );

        $this->tableBuilder->addAction(
            Action::make('view')
                ->label(__('adminhub::tables.actions.promotion-banners.show'))
                ->url(fn ($record) => route('hub.promotion-banners.show', $record->id))
        );
    }

    public function getSearchPlaceholderProperty(): string
    {
        return __('adminhub::tables.search_placeholders.promotion-banners');
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
