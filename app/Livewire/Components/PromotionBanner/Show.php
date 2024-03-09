<?php

declare(strict_types=1);

namespace App\Livewire\Components\PromotionBanner;

use App\Models\PromotionBanner\PromotionBanner;
use Illuminate\View\View;
use Lunar\Hub\Http\Livewire\Traits\Notifies;

class Show extends AbstractPromotionBanner
{
    use Notifies;

    public function mount(PromotionBanner $promotionBanner): void
    {
        $this->promotionBanner = PromotionBanner::withTrashed()->find($promotionBanner->id);
    }

    public function render(): View
    {
        return view('adminhub.livewire.components.promotion-banner.show');
    }

    public function delete(): void
    {
        $this->promotionBanner->delete();
        $this->notify(
            __('adminhub::notifications.promotion-banners.deleted'),
            'hub.promotion-banners.index'
        );
    }

    public function restore(): void
    {
        $this->promotionBanner->restore();
        $this->showRestoreConfirm = false;
        $this->notify(__('adminhub::notifications.promotion-banners.product_restored'));
    }

    protected function getSlotContexts(): array
    {
        return ['promotion-banner.all', 'promotion-banner.create'];
    }
}
