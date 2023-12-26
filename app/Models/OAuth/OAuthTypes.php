<?php

declare(strict_types=1);

namespace App\Models\OAuth;

enum OAuthTypes: string
{
    case GITHUB = 'github';
    case GOOGLE = 'google';

    //    public static function from(int|string $value): static
    //    {
    //        return match($value) {
    //            'github' => self::GITHUB,
    //            'google' => self::GOOGLE,
    //            default => throw new DomainException('Unsupported OAuth type'),
    //        };
    //    }
}
