<?php

declare(strict_types=1);

namespace App\Livewire\Components\PromotionBanner;

use App\Models\PromotionBanner\PromotionBanner;
use Lunar\Hub\Tables\TableBuilder as BaseTableBuilder;

class TableBuilder extends BaseTableBuilder
{
    // TODO move logic to main table class?
    public function getData(): iterable
    {
        $query = PromotionBanner::query()
            ->orderBy($this->sortField, $this->sortDir)
            ->withTrashed();

        if ($this->searchTerm) {
            // TODO fix search
            $query->whereIn('id', PromotionBanner::search($this->searchTerm)
                ->query(fn ($query) => $query->select('id'))
                ->take(500) // TODO magic?
                ->keys()
            );
        }

        $filters = collect($this->queryStringFilters)->filter(function ($value) {
            return (bool) $value;
        });

        foreach ($this->queryExtenders as $qe) {
            call_user_func($qe, $query, $this->searchTerm, $filters);
        }

        $tableFilters = $this->getFilters()->filter(function ($filter) use ($filters) {
            return $filters->has($filter->field);
        });

        foreach ($tableFilters as $filter) {
            call_user_func($filter->getQuery(), $filters, $query);
        }

        return $query->paginate($this->perPage);
    }
}
