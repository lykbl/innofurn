<?php

declare(strict_types=1);

namespace App\Http\Livewire\Components\ChatRoom;

use App\Models\Chat\ChatMessage;
use App\Models\Chat\ChatRoom;
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
