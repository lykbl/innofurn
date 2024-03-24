<?php

declare(strict_types=1);

namespace App\GraphQL\Validators;

use App\Models\Product;
use Nuwave\Lighthouse\Validation\Validator;

final class SearchProductReviewsFiltersInputValidator extends Validator
{
    public function rules(): array
    {
        return [
            'productSlug' => [
                function (string $attribute, mixed $value, callable $fail): void {
                    if (Product::whereHas('urls', fn ($query) => $query->where('slug', $value))->doesntExist()) {
                        $fail('Product does not exist.');
                    }
                },
            ],
        ];
    }
}
