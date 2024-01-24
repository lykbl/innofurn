<?php

declare(strict_types=1);

namespace App\GraphQL\ChatMessage\Types;

use App\Domains\Chat\Models\ChatMessage;
use App\Models\Customer;
use GraphQL\Type\Definition\ResolveInfo;
use Lunar\Hub\Models\Staff;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class Author
{
    public function __invoke(ChatMessage $chatMessage, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Customer|Staff
    {
        return null !== $chatMessage->customer_id ? $chatMessage->customer : $chatMessage->staff;
    }
}
