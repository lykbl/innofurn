<?php

declare(strict_types=1);

namespace App\Models\OAuth;

enum OAuthTypes: string
{
    case GITHUB = 'github';
    case GOOGLE = 'google';
}
