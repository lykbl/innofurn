<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Chat\ChatMessage;
use App\Models\Chat\ChatMessageStatuses;
use App\Models\Chat\ChatRoom;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ChatService
{
    public function chatRoomMessagesQuery(int $chatRoomId): Builder
    {
        return ChatMessage::query()
            ->where('chat_room_id', $chatRoomId)
        ;
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

    public function sendMessageToChatRoom(string $body, int $chatRoomId, ?int $customerId = null, ?int $staffId = null): ChatMessage
    {
        return ChatMessage::create([
            'body'         => $body,
            'chat_room_id' => $chatRoomId,
            'customer_id'  => $customerId,
            'staff_id'     => $staffId,
            'status'       => ChatMessageStatuses::DELIVERED->value,
        ]);
    }
}
