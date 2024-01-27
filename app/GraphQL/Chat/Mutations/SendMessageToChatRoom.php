<?php

declare(strict_types=1);

namespace App\GraphQL\Chat\Mutations;

use App\Domains\Chat\Models\ChatMessage;
use App\Models\User;
use Auth;
use Lunar\Hub\Models\Staff;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class SendMessageToChatRoom extends ChatMutation
{
    public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ChatMessage
    {
        $user = Auth::user();
        if ($user instanceof User) {
            $args['customerId'] = $user->retailCustomer->id;
        }
        if ($user instanceof Staff) {
            $args['staffId'] = $user->id;
        }

        return $this->chatService->sendMessageToChatRoom(...$args);
    }
}
