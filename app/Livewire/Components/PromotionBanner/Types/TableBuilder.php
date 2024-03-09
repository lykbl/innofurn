<?php

declare(strict_types=1);

namespace App\Livewire\Components\PromotionBanner\Types;

use App\Models\PromotionBanner\PromotionBannerType;
use Lunar\Hub\Tables\TableBuilder as BaseTableBuilder;

class TableBuilder extends BaseTableBuilder
{
    public function getData(): iterable
    {
        $query = PromotionBannerType::query()
            ->orderBy($this->sortField, $this->sortDir)
        ;

        if ($this->searchTerm) {
            // TODO fix search
            $query->whereIn('id', PromotionBannerType::search($this->searchTerm)
                ->query(fn ($query) => $query->select('id'))
                ->take(500)
                ->keys());
        }

        $filters = collect($this->queryStringFilters)->filter(function ($value) {
            return (bool) $value;
        });

        foreach ($this->queryExtenders as $qe) {
            call_user_func($qe, $query, $this->searchTerm, $filters);
        }

        // Get the table filters we want to apply.
        $tableFilters = $this->getFilters()->filter(function ($filter) use ($filters) {
            return $filters->has($filter->field);
        });

        foreach ($tableFilters as $filter) {
            call_user_func($filter->getQuery(), $filters, $query);
        }

        return $query->paginate($this->perPage);
    }
}
