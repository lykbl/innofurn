<?php

declare(strict_types=1);

namespace App\Livewire\Chat\Pages;

use App\Domains\Chat\Models\ChatRoom;
use Livewire\Component;

class ChatRoomShow extends Component
{
    public ChatRoom $chatRoom;

    public function mount(ChatRoom $chatRoom): void
    {
        $this->chatRoom = $chatRoom;
    }

    public function render()
    {
        return view('adminhub::pages.chats.show', ['chat' => $this->chatRoom]);
    }
}
