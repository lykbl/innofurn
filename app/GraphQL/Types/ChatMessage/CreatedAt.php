<?php

declare(strict_types=1);

namespace App\GraphQL\Types\ChatMessage;

use App\Models\Chat\ChatMessage;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class CreatedAt
{
    public function __invoke(ChatMessage $chatMessage, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ?string
    {
        return $chatMessage->created_at->toDateTimeString();
    }
}
