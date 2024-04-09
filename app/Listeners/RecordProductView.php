<?php

namespace App\Listeners;

use App\Events\ProductViewed;
use App\Services\ProductViewService;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecordProductView implements ShouldQueue
{
    public function __construct(private ProductViewService $productViewService)
    {
    }

    public function handle(ProductViewed $event): void
    {
        $this->productViewService->recordProductView($event->productId, $event->userId);
    }
}
