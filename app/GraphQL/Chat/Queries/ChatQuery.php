<?php

declare(strict_types=1);

namespace App\GraphQL\Chat\Queries;

use App\Domains\Chat\ChatService;
use App\GraphQL\ResolverInterface;

abstract class ChatQuery implements ResolverInterface
{
    public function __construct(protected ChatService $chatService)
    {
    }
}
