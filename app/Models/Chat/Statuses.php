<?php

namespace App\Models\Chat;

enum Statuses: string
{
    case DELIVERED = 'delivered';
    case READ = 'read';
}
