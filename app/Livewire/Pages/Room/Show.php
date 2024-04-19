<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Room;

use App\Models\Room;
use Livewire\Component;

class Show extends Component
{
    public Room $room;

    public function mount(int $id): void
    {
        $this->room = Room::withTrashed()->find($id);
    }

    public function render()
    {
        return view('adminhub.livewire.pages.rooms.show', ['room' => $this->room]);
    }
}
