<?php

declare(strict_types=1);

namespace App\GraphQL\Chat\Mutations;

use App\Domains\Chat\ChatService;
use App\GraphQL\ResolverInterface;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

abstract class ChatMutation implements ResolverInterface
{
    public function __construct(protected ChatService $chatService)
    {
    }

    abstract public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo);
}
