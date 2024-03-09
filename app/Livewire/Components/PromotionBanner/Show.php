<?php

declare(strict_types=1);

namespace App\Livewire\Components\PromotionBanner;

use App\Models\PromotionBanner\PromotionBanner;
use Illuminate\View\View;

class Show extends AbstractPromotionBanner
{
    public function mount(PromotionBanner $promotionBanner): void
    {
        $this->promotionBanner = PromotionBanner::find($promotionBanner->id);
    }

    public function render(): View
    {
        return view('adminhub.livewire.components.promotion-banner.create');
    }

    protected function getSlotContexts(): array
    {
        return ['promotion-banner.all', 'promotion-banner.create'];
    }
}
