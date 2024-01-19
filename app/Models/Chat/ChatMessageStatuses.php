<?php

namespace App\Models\Chat;

enum ChatMessageStatuses: string
{
    case DELIVERED = 'DELIVERED';
    case READ = 'READ';
}
