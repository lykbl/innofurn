<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\Chat;

use App\GraphQL\ResolverInterface;
use App\Services\Chat\ChatService;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

abstract class ChatMutation implements ResolverInterface
{
    public function __construct(protected ChatService $chatService)
    {
    }

    abstract public function __invoke(mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo);
}
