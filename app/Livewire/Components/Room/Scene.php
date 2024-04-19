<?php

declare(strict_types=1);

namespace App\Livewire\Components\Room;

use Illuminate\View\View;
use Livewire\Component;

class Scene extends Component
{
    public function mount($glb_location): void
    {
        $this->glb_location = $glb_location;
    }

    public function render(): View
    {
        return view('adminhub.livewire.components.room.scene');
    }
}
