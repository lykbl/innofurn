<?php

declare(strict_types=1);

namespace App\GraphQL\Directives;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;

final class ReverseDirective extends BaseDirective implements FieldMiddleware
{
    public static function definition(): string
    {
        return /* @lang GraphQL */ <<<'GRAPHQL'
directive @reverse on FIELD_DEFINITION
GRAPHQL;
    }

    public function handleField(FieldValue $fieldValue): void
    {
        $fieldValue->resultHandler(function (LengthAwarePaginator $result) {
            return $result->setCollection($result->getCollection()->reverse());
        });
    }
}
