<?php

declare(strict_types=1);

namespace App\GraphQL\Subscriptions\Chat;

use Illuminate\Http\Request;
use Nuwave\Lighthouse\Schema\Types\GraphQLSubscription;
use Nuwave\Lighthouse\Subscriptions\Subscriber;

final class UpdateChatRoom extends GraphQLSubscription
{
    public function authorize(Subscriber $subscriber, Request $request): bool
    {
        return true;
        $chatRoomId = (int) $subscriber->args['chatRoomId']; // TODO verify this when connected to frontend
        if ($user = $subscriber->context->user) {
            $activeChatRoom = $user->retailCustomer->activeChatRoom;

            return $activeChatRoom->id === $chatRoomId;
        }

        if (session()->has('chatRoomId')) {
            return session()->get('chatRoomId') === $chatRoomId;
        }

        return false;
    }

    public function filter(Subscriber $subscriber, mixed $root): bool
    {
        return true;
        if ($root->customer_id) {
            return false;
        } else {
            return true;
        }
    }
}
