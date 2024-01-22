<?php

namespace App\Http\Livewire\Pages\Chat;

use Illuminate\View\View;
use Livewire\Component;

class ChatsIndex extends Component
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
