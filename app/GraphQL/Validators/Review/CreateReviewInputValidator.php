<?php

declare(strict_types=1);

namespace App\GraphQL\Validators\Review;

use App\Models\Review\Review;
use Closure;
use Nuwave\Lighthouse\Validation\Validator;

final class CreateReviewInputValidator extends Validator
{
    public function rules(): array
    {
        return [
            'productVariantId' => [
                'exists:lunar_product_variants,id',
                function (string $attribute, mixed $value, Closure $fail): void { // TODO add custom rule?
                    $user = auth()->user();
                    if (!$user) {
                        $fail('User is not authenticated');
                    } elseif (Review::withUnapproved()->where([['user_id', '=', $user->id], ['product_variant_id', '=', $value]])->first()) {
                        $fail('Product review was already created');
                    }
                },
            ],
        ];
    }
}
