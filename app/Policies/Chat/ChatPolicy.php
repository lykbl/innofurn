<?php

declare(strict_types=1);

namespace App\Policies\Chat;

use App\Models\Chat\ChatRoom;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChatPolicy
{
    public function sendMessage(User $user, mixed $args): Response
    {
        $chatRoom = ChatRoom::find($args['chatRoomId']);
        $customer = $user->retailCustomer;

        return
            $customer->id === $chatRoom->customer_id
            ? Response::allow()
            : Response::deny('You are not allowed to send messages to this chat room.');
    }
}
