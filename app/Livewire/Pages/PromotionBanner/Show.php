<?php

declare(strict_types=1);

namespace App\Livewire\Pages\PromotionBanner;

use App\Models\PromotionBanner\PromotionBanner;
use Livewire\Component;

class Show extends Component
{
    public PromotionBanner $promotionBanner;

    public function mount(PromotionBanner $promotionBanner): void
    {
        $this->promotionBanner = $promotionBanner;
    }

    public function render()
    {
        return view('adminhub.livewire.pages.promotion-banners.show', ['promotionBanner' => $this->promotionBanner]);
    }
}
