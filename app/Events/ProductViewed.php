<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductViewed
{
    use Dispatchable, SerializesModels;

    public int $productId;
    public int $userId;

    public function __construct(
        Product $product,
    ) {
        $this->userId = \Auth::id();
        $this->productId = $product->id;
    }
}
