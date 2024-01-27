<?php

declare(strict_types=1);

namespace App\GraphQL\Chat\Subscriptions;

use App\Models\Customer;
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
        if ($author instanceof User) {
            $chatRoomId     = $subscriber->args['chatRoomId'];
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
        $author       = $root->author;
        $subscriberId = $subscriber->context->user->id;

        return match (true) {
            $author instanceof Staff    => $subscriberId !== $author->id,
            $author instanceof Customer => $subscriberId !== $author->user->id,
            default                     => false,
        };
    }

    public function encodeTopic(Subscriber $subscriber, string $fieldName): string
    {
        return 'chatRoom.'.$subscriber->args['chatRoomId'];
    }

    public function decodeTopic(string $fieldName, mixed $root): string
    {
        return 'chatRoom.'.$root->chatRoom->id;
    }
}
