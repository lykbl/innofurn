<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    private const MAX_EMAIL_CHANGES_PER_DAY = 3;

    public function updateEmail(User $user, mixed $args): Response
    {
        $limitReached = $user
            ->emailChangeHistory()
            ->withTrashed()
            ->where('created_at', '>=', now()->subDay())
            ->count() >= self::MAX_EMAIL_CHANGES_PER_DAY
        ;

        if ($limitReached) {
            return Response::deny('You have reached the maximum number of email changes per day.');
        }

        return Response::allow();
    }
}
