<?php

declare(strict_types=1);

namespace App\Models\Chat;

enum ChatMessageStatuses: string
{
    case DELIVERED = 'delivered';
    case READ      = 'read';
}
