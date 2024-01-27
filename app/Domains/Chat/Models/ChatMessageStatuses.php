<?php

declare(strict_types=1);

namespace App\Domains\Chat\Models;

enum ChatMessageStatuses: string
{
    case DELIVERED = 'delivered';
    case READ      = 'read';
}
