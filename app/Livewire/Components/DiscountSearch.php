<?php

declare(strict_types=1);

namespace App\Livewire\Components;

use App\Models\Discount;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Component;

class DiscountSearch extends Component
{
    public bool $showBrowser = false;

    public ?string $searchTerm = null;

    public int $maxResults = 50;

    public ?int $selectedDiscountId = null;

    public ?string $ref = null;

    public function rules(): array
    {
        return [
            'searchTerm' => 'required|string|max:255',
        ];
    }

    public function updatedShowBrowser(): void
    {
        $this->searchTerm = null;
    }

    public function selectDiscount(int $id): void
    {
        $this->selectedDiscountId = $id;
    }

    public function removeDiscount(): void
    {
        $this->selectedDiscountId = null;
    }

    public function getResultsProperty(): ?LengthAwarePaginator
    {
        if (!$this->searchTerm) {
            return null;
        }

        return Discount::where('name', 'like', '%'.$this->searchTerm.'%')->paginate($this->maxResults);
    }

    public function triggerSelect(): void
    {
        $this->emit('discountSearch.selected', $this->selectedDiscountId, $this->ref);
        $this->showBrowser = false;
    }

    public function render(): View
    {
        return view('adminhub.livewire.components.discount.search');
    }
}
