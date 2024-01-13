<?php

declare(strict_types=1);

namespace App\Services\Chat;

use App\Models\Chat\ChatMessage;
use App\Models\Chat\ChatRoom;
use App\Models\User;
use Illuminate\Support\Collection;

class ChatService
{
    public function getChatRoomMessages(?User $user, int $first, int $page)
    {
        $activeChatRoom = $user->retailCustomer->activeChatRoom;
        $query = $activeChatRoom
            ->messages()
            ->limit($first)
            ->offset(($page - 1) * $first)
            ->orderBy('created_at', 'desc');

        /** @var Collection $messages */
        $messages = $query->get();
        $total = $query->count();

        return [$messages->reverse(), $total];
    }
    public function createChatRoom(?User $user): ChatRoom
    {
        if ($user) {
            $activeChatRoom = $user->retailCustomer->activeChatRoom;

            if (!$activeChatRoom->exists) {
                $activeChatRoom = ChatRoom::create([
                    'customer_id' => $user->retailCustomer->id,
                ]);
                $activeChatRoom->save();
            }
        } elseif (session()->has('chatRoomId')) {
            $activeChatRoom = ChatRoom::find(session()->get('chatRoomId'));
        } else {
            $activeChatRoom = ChatRoom::create();
            $activeChatRoom->save();
            session()->put('chatRoomId', $activeChatRoom->id);
        }

        return $activeChatRoom;
    }

    public function sendMessageToChatRoom(string $body, int $chatRoomId, int $customerId = null, int $staffId = null): ChatMessage
    {
        return ChatMessage::create([
            'body'         => $body,
            'chat_room_id' => $chatRoomId,
            'customer_id'  => $customerId,
            'staff_id'     => $staffId,
        ]);
    }
}
