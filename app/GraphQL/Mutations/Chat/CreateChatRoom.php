<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Chat;

use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateChatRoom extends ChatMutation
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $chatRoom = $this->chatService->createChatRoom(Auth::user());

        return [
            'record'   => $chatRoom,
            'recordId' => $chatRoom->id,
            'query'    => [],
        ];
    }
}
