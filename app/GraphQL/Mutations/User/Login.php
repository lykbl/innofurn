<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use GraphQL\Error\Error;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

final class Login
{
    /**
     * @throws Error
     */
    public function __invoke($_, array $args): Authenticatable
    {
        $guard = Auth::guard(Arr::first(config('sanctum.guard', 'web')));

        if (!$guard->attempt($args)) {
            throw new Error('Invalid credentials.');
        }

        return $guard->user();
    }
}
