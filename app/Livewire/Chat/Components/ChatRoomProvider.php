<?php

declare(strict_types=1);

namespace App\Livewire\Chat\Components;

use App\Domains\Chat\ChatService;
use App\Domains\Chat\Models\ChatMessage;
use Livewire\Component;

class ChatRoomProvider extends Component
{
    public bool $newMessageReceived = false;

    public $messages;

    public $listeners = ['updateChatRoom' => 'receiveNewMessage'];

    public const PAGE_SIZE = 5;

    public function mount(ChatService $chatService): void
    {
        $query = $chatService->chatRoomMessagesQuery(1);

        $this->messages = $query
            ->limit(self::PAGE_SIZE)
            ->orderBy('created_at', 'desc')
            ->get()
            ->reverse()
        ;
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
