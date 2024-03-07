<?php

declare(strict_types=1);

namespace App\Livewire\Pages\PromotionBanner\Types;

use App\Models\PromotionBanner\PromotionBannerType;
use Livewire\Component;

class Show extends Component
{
    public PromotionBannerType $promotionBannerType;

    public function mount(PromotionBannerType $promotionBannerType): void
    {
        $this->promotionBannerType = $promotionBannerType;
    }

    public function render()
    {
        return view('adminhub.livewire.pages.promotion-banners.types.show', ['promotionBannerType' => $this->promotionBannerType]);
    }
}
