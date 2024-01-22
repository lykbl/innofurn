<?php

declare(strict_types=1);

namespace App\Http\Livewire\Components\ChatRoom;

use App\Models\Chat\ChatRoom;
use Lunar\Hub\Tables\TableBuilder;

class ChatRoomsTableBuilder extends TableBuilder
{
    // TODO move logic to main table class?
    public function getData(): iterable
    {
        $query = ChatRoom::query()
            ->orderBy($this->sortField, $this->sortDir)
            ->withTrashed();

        if ($this->searchTerm) {
            // TODO fix search
            $query->whereIn('id', ChatRoom::search($this->searchTerm)
                ->query(fn ($query) => $query->select('id'))
                ->take(500) // TODO magic?
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
