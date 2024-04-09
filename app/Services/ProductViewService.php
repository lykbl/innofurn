<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ProductView;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ProductViewService
{
    public const MAX_PRODUCT_VIEWS_COUNT = 15;

    /**
     * @return Collection<ProductView>
     */
    public function recentlyViewedProducts(User $user)
    {
        $productVariantViews = ProductView::query()
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get()
        ;

        return $productVariantViews;
    }

    public function recordProductView(
        int $productId,
        int $userId,
    ): ProductView {
        $productViewsCount = ProductView::query()
            ->where([
                ['product_id', '!=', $productId],
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

        $productView = ProductView::query()
            ->upsert(
                values: ['product_id' => $productId, 'user_id' => $userId],
                uniqueBy: ['product_id', 'user_id'],
                update: ['created_at' => now()]
            )
        ;

        return $productView;
    }
}
