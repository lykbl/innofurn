<?php

declare(strict_types=1);

namespace App\Livewire\Components\Room;

use App\Livewire\Components\PromotionBanner\AbstractPromotionBanner;
use App\Models\PromotionBanner\PromotionBanner;
use App\Models\PromotionBanner\PromotionBannerType;
use Illuminate\View\View;

class Show extends AbstractPromotionBanner
{
    public function mount(): void
    {
        $this->promotionBanner = new PromotionBanner([
            'status'                   => 'draft',
            'promotion_banner_type_id' => PromotionBannerType::first()->id,
        ]);
    }

    public function render(): View
    {
        return view('adminhub.livewire.components.promotion-banner.show');
    }

    protected function getSlotContexts(): array
    {
        return ['promotion-banner.all', 'promotion-banner.create'];
    }
}
