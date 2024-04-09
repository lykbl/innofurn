<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Product;
use Auth;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductViewed
{
    use Dispatchable;
    use SerializesModels;

    public int $productId;
    public int $userId;

    public function __construct(
        Product $product,
    ) {
        $this->userId    = Auth::id();
        $this->productId = $product->id;
    }
}
