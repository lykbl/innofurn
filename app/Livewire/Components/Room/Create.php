<?php

declare(strict_types=1);

namespace App\Livewire\Components\Room;

use App\Models\Room;
use Illuminate\View\View;

class Create extends AbstractRoom
{
    public function mount(): void
    {
        $this->room = new Room([
            'attribute_data' => [],
        ]);
    }

    public function render(): View
    {
        return view('adminhub.livewire.components.room.show');
    }

    protected function getSlotContexts(): array
    {
        return ['room.all', 'room.create'];
    }

    public function getAvailableAttributesProperty()
    {
        // TODO: Implement getAvailableAttributesProperty() method.
    }
}
