<?php

declare(strict_types=1);

namespace App\Policies\Review;

use App\Models\Review\Review;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReviewPolicy
{
    public function create(User $user, mixed $args): Response
    {
        return
            Review::withUnapproved()->where([['user_id', '=', $user->id], ['product_variant_id', '=', $args['productVariantId']]])->doesntExist()
                ? Response::allow()
                : Response::deny('Only one review per product.');
    }
}
