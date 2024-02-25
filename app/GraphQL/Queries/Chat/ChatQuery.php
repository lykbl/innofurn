<?php

declare(strict_types=1);

namespace App\GraphQL\Queries\Chat;

use App\GraphQL\ResolverInterface;
use App\Services\ChatService;

abstract class ChatQuery implements ResolverInterface
{
    public function __construct(protected ChatService $chatService)
    {
    }
}
