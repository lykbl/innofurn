<?php

declare(strict_types=1);

namespace App\GraphQL\Validators;

use App\Models\Collection;
use Nuwave\Lighthouse\Validation\Validator;

final class ProductVariantsForCollectionFilterInputValidator extends Validator
{
    public function rules(): array
    {
        return [
            'collection' => [
                function (string $attribute, mixed $value, callable $fail): void {
                    if (Collection::whereHas('urls', fn ($query) => $query->where('slug', $value))->doesntExist()) {
                        $fail('The collection does not exist.');
                    }
                },
            ],
        ];
    }
}
