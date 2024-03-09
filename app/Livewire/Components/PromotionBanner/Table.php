<?php

declare(strict_types=1);

namespace App\Livewire\Components\PromotionBanner;

use App\Models\PromotionBanner\PromotionBanner;
use Lunar\LivewireTables\Components\Actions\Action;
use Lunar\LivewireTables\Components\Columns\TextColumn;
use Lunar\LivewireTables\Components\Table as BaseTable;

class Table extends BaseTable
{
    protected $tableBuilderBinding = TableBuilder::class;

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
        $this->tableBuilder->baseColumns([
            TextColumn::make('id'),
            TextColumn::make('name')->value(fn (PromotionBanner $promotionBanner) => $promotionBanner->translateAttribute('name')),
            TextColumn::make('description')->value(fn (PromotionBanner $promotionBanner) => $promotionBanner->translateAttribute('description')),
            TextColumn::make('discount')->value(fn (PromotionBanner $promotionBanner) => "{$promotionBanner->discount->name} ({$promotionBanner->discount->id})"),
            TextColumn::make('starts_at')->value(fn (PromotionBanner $promotionBanner) => $promotionBanner->discount->starts_at->format('Y-m-d H:i')),
            TextColumn::make('ends_at')->value(fn (PromotionBanner $promotionBanner) => $promotionBanner->discount->ends_at->format('Y-m-d H:i')),
        ]);

        $this->tableBuilder->addAction(Action::make('view')->label(__('adminhub::tables.actions.promotion-banners.show'))->url(function ($record) {
            return route('hub.promotion-banners.show', $record->id);
        }));
    }

    /**
     * Return the search placeholder.
     *
     * @return string
     */
    public function getSearchPlaceholderProperty(): string
    {
        return __('adminhub::tables.search_placeholders.promotion-banner');
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
