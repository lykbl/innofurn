<?php

declare(strict_types=1);

namespace App\Livewire\Chat\Components;

use App\Domains\Chat\ChatService;
use App\Domains\Chat\Models\ChatMessage;
use App\Domains\Chat\Models\ChatRoom;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Nuwave\Lighthouse\Execution\Utils\Subscription;

class ChatRoomProvider extends Component
{
    private const PAGE_SIZE = 5;

    /**
     * @var Collection<ChatMessage>
     */
    public Collection $chatMessages;

    public ChatRoom $chatRoom;

    public $listeners = ['updateChatRoom' => 'receiveNewMessage'];

    public $input = '';

    protected $rules = [
        'input' => 'required|string',
    ];

    public function mount(ChatRoom $chatRoom, ChatService $chatService): void
    {
        $this->chatRoom = $chatRoom;
        $query          = $chatService->chatRoomMessagesQuery($this->chatRoom->id);

        $this->chatMessages = $query
            ->limit(self::PAGE_SIZE)
            ->orderBy('created_at', 'desc')
            ->get()
            ->reverse()
        ;
    }

    public function receiveNewMessage(ChatMessage $newMessage): void
    {
        $this->chatMessages->push($newMessage);
    }

    public function save(ChatService $chatService): void
    {
        $this->validateOnly('input');

        $staff       = Auth::guard('staff')->user();
        $chatMessage = $chatService->sendMessageToChatRoom($this->input, $this->chatRoom->id, staffId: $staff->id);
        $this->chatMessages->push($chatMessage);
        Subscription::broadcast('updateChatRoom', $chatMessage);
        $this->reset(['input']);
    }

    public function render()
    {
        return view('adminhub.livewire.components.chat.chat-room-provider');
    }
}
