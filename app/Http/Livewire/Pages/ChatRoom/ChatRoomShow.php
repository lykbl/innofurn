<?php

declare(strict_types=1);

namespace App\Http\Livewire\Pages\ChatRoom;

use App\Models\Chat\ChatMessage;
use Livewire\Component;
use Nuwave\Lighthouse\Subscriptions\Contracts\StoresSubscriptions;
use Nuwave\Lighthouse\Subscriptions\Subscriber;

class ChatRoomShow extends Component
{
    public $newMessageReceived = false;

    public $messages = [];

    public $listeners = ['updateChatRoom' => 'receieveNewMessage'];

    public function receieveNewMessage(ChatMessage $newMessage)
    {
        $this->newMessageReceived = true;

        $this->messages[] = $newMessage;
    }

    public function render()
    {
        return view('adminhub.livewire.pages.chats.show');
    }
}
