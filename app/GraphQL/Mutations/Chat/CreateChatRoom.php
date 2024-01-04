<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Chat;

use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateChatRoom extends ChatMutation
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user     = auth()->user();
        $chatRoom = $this->chatService->createChatRoom($user);

        return [
            'record'   => $chatRoom,
            'recordId' => $chatRoom->id,
            'query'    => [],
        ];
    }
}
