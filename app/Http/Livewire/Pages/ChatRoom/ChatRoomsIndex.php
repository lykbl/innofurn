<?php

declare(strict_types=1);

namespace App\Http\Livewire\Pages\ChatRoom;

use Illuminate\View\View;
use Livewire\Component;

class ChatRoomsIndex extends Component
{
    /**
     * Render the livewire component.
     *
     * @return View
     */
    public function render()
    {
        return view('adminhub.livewire.pages.chats.index');
    }
}
