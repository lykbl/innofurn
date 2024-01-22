<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Chat;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ChatRoomMessages extends ChatQuery
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user = Auth::user();
        $page = $args['page'] ?? 1;
        if (0 === $page) {
            $page = 1;
        }

        //        $messagesBuilder = $this->chatService->getChatRoomMessages($user, $args['first'], $page);
        [$messages, $total] = $this->chatService->getChatRoomMessages($user, $args['first'], $page);

        // TODO - fix pagination
        //        return $messagesBuilder;
        return new LengthAwarePaginator(
            $messages,
            $total,
            $args['first'],
            $page,
        );
    }
}
