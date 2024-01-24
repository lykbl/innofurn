<?php

declare(strict_types=1);

namespace App\GraphQL\Subscriptions\Chat;

use App\Models\User;
use Illuminate\Http\Request;
use Lunar\Hub\Models\Staff;
use Nuwave\Lighthouse\Schema\Types\GraphQLSubscription;
use Nuwave\Lighthouse\Subscriptions\Subscriber;

final class UpdateChatRoom extends GraphQLSubscription
{
    public function authorize(Subscriber $subscriber, Request $request): bool
    {
        $author = $subscriber->context->user;
        //        $chatRoomId = (int) $subscriber->args['chatRoomId']; // TODO verify this when connected to frontend
        if ($author instanceof User) {
            $chatRoomId     = 1;
            $activeChatRoom = $author->retailCustomer->activeChatRoom;

            return $activeChatRoom->id === $chatRoomId;
        }
        if ($author instanceof Staff) {
            return true;
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

    public function encodeTopic(Subscriber $subscriber, string $fieldName): string
    {
        //        return 'chatRoom.' . $subscriber->args['chatRoomId'];
        return 'chatRoom.1';
    }

    public function decodeTopic(string $fieldName, mixed $root): string
    {
        $chatRoomId = $root->chatRoom->id;

        return 'chatRoom.'.$chatRoomId;
    }
}
