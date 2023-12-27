<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

final class Logout
{
    public function __invoke($_, array $args): Authenticatable
    {
        $guard = Auth::guard(Arr::first(config('sanctum.guard', 'web')));
        $user  = $guard->user();
        $guard->logout();

        return $user;
    }
}
