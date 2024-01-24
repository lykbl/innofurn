<?php

declare(strict_types=1);

namespace App\GraphQL\ChatMessage\Types;

use App\Domains\Chat\Models\ChatMessage;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class CreatedAt
{
    public function __invoke(ChatMessage $chatMessage, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): string
    {
        return $chatMessage->created_at->toDateTimeString();
    }
}
