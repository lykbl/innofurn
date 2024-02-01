<?php

declare(strict_types=1);

namespace App\Livewire\Chat\Pages;

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
//        return view('adminhub::pages.chats.index');
    }
}
