<?php

declare(strict_types=1);

namespace App\GraphQL\Types\ProductVariant;

use App\Domains\ProductVariant\ProductVariant;
use App\Models\Currency;
use App\Models\Price as PriceModel;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Price
{
    /**
     * @param ProductVariant $productVariant
     * @param array          $args
     * @param GraphQLContext $context
     * @param ResolveInfo    $resolveInfo
     *
     * @return PriceModel
     */
    public function __invoke(ProductVariant $productVariant, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): PriceModel
    {
        $defaultCurrency = Currency::getDefault();

        return $productVariant
            ->prices()
            ->where('currency_id', '=', $args['currency_id'] ?? $defaultCurrency->id)
            ->get()
            ->first();
    }
}
