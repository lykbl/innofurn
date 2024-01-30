<?php

declare(strict_types=1);

namespace App\GraphQL\Types;

use App\Domains\ProductVariant\ProductVariant;
use App\GraphQL\Exceptions\UnknownDimensionException;
use Cartalyst\Converter\Converter;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

abstract class Dimension
{
    /**
     * @param ProductVariant $productVariant
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $resolveInfo
     *
     * @return ?Converter
     *
     * @throws UnknownDimensionException
     */
    public function __invoke(ProductVariant $productVariant, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ?Converter
    {
        if (!$productVariant->isShippable()) {
            return null;
        }

        return $productVariant->{$resolveInfo->fieldName}->to($this->convertToUnits($args['to'], $resolveInfo->fieldName));
    }

    /**
     * @throws UnknownDimensionException
     */
    private function convertToUnits(string $to, string $dimensionType): string
    {
        return match ($dimensionType) {
            'width', 'height', 'length' => "length.$to",
            'weight' => "weight.$to",
            default  => throw new UnknownDimensionException($dimensionType),
        };
    }
}
