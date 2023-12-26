<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations\User;

use Illuminate\Contracts\Auth\Authenticatable;

final class Logout
{
    public function __invoke($_, array $args): Authenticatable
    {
        $user = auth()->user();
        auth()->logout();

        return $user;
    }
}
