<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\ProductVariant;

use App\Models\ProductVariant;

final class DecreaseStock
{
    /** @param array{id: string} $args */
    public function __invoke(mixed $root, array $args)
    {
        $productVariant = ProductVariant::find($args['id']);
        $productVariant->stock--;
        $productVariant->save();

        return ['record' => $productVariant];
    }
}
