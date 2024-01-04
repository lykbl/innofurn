<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Chat;

use App\Models\Chat\ChatMessage;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SendMessageToChatRoom extends ChatMutation
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ChatMessage
    {
        return $this->chatService->sendMessageToChatRoom(...$args);
    }
}
