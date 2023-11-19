<?php

namespace App\GraphQL\Types;

use App\GraphQL\Exceptions\UnknownDimensionException;
use App\Models\ProductVariant;
use Cartalyst\Converter\Converter;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

abstract class Dimension
{
    /**
     * @param ProductVariant $productVariant
     * @param array $args
     * @param GraphQLContext $context
     * @param ResolveInfo $resolveInfo
     * @return Converter
     *
     * @throws UnknownDimensionException
     */
    public function __invoke(ProductVariant $productVariant, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Converter
    {
        return $productVariant->{$resolveInfo->fieldName}->to($this->convertToUnits($args['to'], $resolveInfo->fieldName));
    }

    /**
     * @throws UnknownDimensionException
     */
    private function convertToUnits(string $to, string $dimensionType): string
    {
        return match($dimensionType) {
            'width', 'height', 'length' => "length.$to",
            'weight' => "weight.$to",
            //TODO add exception
            default => throw new UnknownDimensionException($dimensionType),
        };
    }
}
