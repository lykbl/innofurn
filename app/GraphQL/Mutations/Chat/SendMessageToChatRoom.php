<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Chat;

use App\Models\Chat\ChatMessage;
use Auth;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SendMessageToChatRoom extends ChatMutation
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ChatMessage
    {
        $customerId = Auth::user()?->retailCustomer->id;

        sleep(2);
        $failRate = random_int(0, 100);
        if ($failRate < 30) {
            throw new \Exception('Failed to send message');
        }

        return $this->chatService->sendMessageToChatRoom(...$args, customerId: $customerId);
    }
}
