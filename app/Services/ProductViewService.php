<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\ProductView;

class ProductViewService
{
    public const MAX_PRODUCT_VIEWS_COUNT = 15;

    public function recordProductView(
        string $slug,
        int $userId,
    ): bool {
        $product = Product::withSlug($slug)->first();
        if (!$product) {
            return false;
        }

        $productViewsCount = ProductView::query()
            ->where([
                ['product_id', '!=', $product->id],
                ['user_id', '=', $userId],
            ])
            ->count()
        ;

        $productViewsToDelete = $productViewsCount - (self::MAX_PRODUCT_VIEWS_COUNT - 1);
        if ($productViewsToDelete > 0) {
            ProductView::query()
                ->where([
                    ['user_id', '=', $userId],
                ])
                ->oldest()
                ->limit($productViewsToDelete)
                ->delete()
            ;
        }

        ProductView::query()
            ->upsert(
                values: ['product_id' => $product->id, 'user_id' => $userId],
                uniqueBy: ['product_id', 'user_id'],
                update: ['created_at' => now()]
            )
        ;

        return true;
    }
}
