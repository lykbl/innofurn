<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Chat;

use Illuminate\Database\Eloquent\Builder;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ChatRoomMessages extends ChatQuery
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Builder
    {
        return $this->chatService->chatRoomMessagesQuery($args['chatRoomId']);
    }
}
