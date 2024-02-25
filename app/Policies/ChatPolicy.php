<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Chat\ChatRoom;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Lunar\Hub\Models\Staff;

class ChatPolicy
{
    public function sendMessage(User|Staff $user, mixed $args): Response
    {
        if ($user instanceof Staff) {
            return Response::allow();
        }

        $chatRoom = ChatRoom::find($args['chatRoomId']);
        $customer = $user->retailCustomer;

        return
            $customer->id === $chatRoom->customer_id
            ? Response::allow()
            : Response::deny('You are not allowed to send messages to this chat room.');
    }
}
