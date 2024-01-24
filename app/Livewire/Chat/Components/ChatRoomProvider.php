<?php

declare(strict_types=1);

namespace App\Livewire\Chat\Components;

use App\Domains\Chat\Models\ChatMessage;
use App\Domains\Chat\Models\ChatRoom;
use Livewire\Component;

class ChatRoomProvider extends Component
{
    public bool $newMessageReceived = false;

    public $messages;

    public $listeners = ['updateChatRoom' => 'receiveNewMessage'];

    public function mount(): void
    {
        $this->messages = ChatRoom::find(1)->messages;
    }

    public function receiveNewMessage(ChatMessage $newMessage): void
    {
        $this->newMessageReceived = true;

        $this->messages[] = $newMessage;
    }

    public function render()
    {
        return view('adminhub.livewire.components.chat.chat-room-provider');
    }
}
